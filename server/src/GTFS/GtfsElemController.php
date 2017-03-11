<?php

namespace HackQC17_8_Euclide\GFTS;

class GtfsElemController {
    public $CurGtfsCtrl;
    public $list;
    public $timeLoad;
    public $timeInsert;
    public $table;
    public $DB_fields_mapping;
    function __construct($path, $CurGtfsCtrl) {
        $this->timeLoad = null;
        $this->timeInsert = null;
        $this->CurGtfsCtrl = $CurGtfsCtrl;
        $this->list = [];
        if (file_exists($path))
            $this->readFile($path);
        $this->count = count($this->list);
    }

    public function readFile($path) {
        $start = microtime(true);

        $fp = fopen($path, 'r');
        $count = -1;
        $keys = [];
        $keysHasKey = [];
        while (($data = fgetcsv($fp, 0, ",")) !== FALSE) {
            if ($count < 0) {
                $keys = $data;
                foreach ($keys as $k => $v)
                    $keysHasKey[$v] = trim($k);
            } else {
                $elem = [];
                foreach ($data as $k => $v)
                    $elem[$keys[$k]] = trim($v);
                $this->parseData($elem);
            }
            $count ++;
        }
        fclose($fp);

        $this->timeLoad = round(microtime(true)-$start, 5);
        return $this->list;
    }

    public function export() {
        global $DB;
        $this->DB_fields_mapping;
        $start = microtime(true);
        $headSql = "INSERT INTO $this->table (".implode(', ', array_keys($this->DB_fields_mapping)).") VALUES ";
        $rowsSql = []; $count = 0;
        foreach ($this->list as $key => $elem) {
            $elem = $this->parseDateForInsert($elem);
            $fieldsForInsert = [];
            foreach ($this->DB_fields_mapping as $key => $type)
                $fieldsForInsert[$key] = ($type=='string')?'"'.str_replace('"', '', $elem[$key]).'"':((empty($elem[$key]) && $elem[$key]!==0)?'null':$elem[$key]*1);
            $rowsSql[] = str_replace('""', "NULL", '('.implode(', ', $fieldsForInsert).')');
            $count++;
        }
        if ($count) {
            $DB->exec($headSql . implode(", ", $rowsSql));
            $this->timeInsert = round(microtime(true)-$start, 2);
            echo "  import $this->table: ".$count." en ".$this->timeInsert."s<br>\n";
        }
        // Fetch data !!
        $this->fetchDataDB();
    }
    public function fetchDataDB() {
        global $DB;
        $res = $DB->query("SELECT * FROM $this->table");
        $this->list = [];
        foreach ($res as $val) {
            $this->list[$val['pk']] = $this->mapValue($val);
        }
    }
    public function mapValue($elem) {
        foreach ($this->DB_fields_mapping as $key => $type)
            $elem[$key] = ($type == 'int') ? $elem[$key]*1 : $elem[$key];
        return $elem;
    }
    public function parseData($elem) {
        $this->list[] = $elem;
    }
    public function parseDateForInsert($elem) {
        return $elem;
    }
    public function log($msg){
        file_put_contents($this->CurGtfsCtrl->log_path.'log_analyse.csv', date("Y-m-d H:i:s").': '.$msg."\n", FILE_APPEND);
    }
    public static function hourMinToSec($hourMin) {
        $hourMin = explode(':', $hourMin);
        return $hourMin[0]*60*60 + $hourMin[1]*60 + (!empty($hourMin[2]))?$hourMin:0;
    }
    public static function yyyymmddToIsoDate($yyyymmdd) {
        if (empty($yyyymmdd))
            return null;
        return substr($yyyymmdd, 0, 4).'-'.substr($yyyymmdd, 4, 2).'-'.substr($yyyymmdd, 6, 2);
    }
    public function updateSpatialExtent(&$spatialExtent, $coords) {
        if (empty($spatialExtent))
            $spatialExtent = ['lng'=>[$coords[0], $coords[0]], 'lat'=>[$coords[1], $coords[1]]];
        else {
            if ($spatialExtent['lng'][0] > $coords[0]) // min
                $spatialExtent['lng'][0] = $coords[0];
            if ($spatialExtent['lng'][1] < $coords[0]) // max
                $spatialExtent['lng'][1] = $coords[0];
            if ($spatialExtent['lat'][0] > $coords[1]) // min
                $spatialExtent['lat'][0] = $coords[1];
            if ($spatialExtent['lat'][1] < $coords[1]) // max
                $spatialExtent['lat'][1] = $coords[1];
        }
    }
}