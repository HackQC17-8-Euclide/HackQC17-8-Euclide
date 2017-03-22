<?php

namespace HackQC17_8_Euclide\GFTS;

class GtfsElemController {
    public $CurGtfsCtrl;
    public $list;
    public $options;
    public $timeLoad;
    public $timeInsert;
    public $table;
    public $DB_fields_mapping;
    function __construct($path, $CurGtfsCtrl, $options=[]) {
        $this->options = $options;
        $this->timeLoad = null;
        $this->timeInsert = 0;
        $this->primaryFieldKeyList = 'pk';
        $this->CurGtfsCtrl = $CurGtfsCtrl;
        $this->list = [];
        $separator = !empty($this->options['separator'])?$this->options['separator']:',';
        if (file_exists($path))
            $this->readFile($path, $separator);
        $this->count = count($this->list);
    }

    public function readFile($path, $separator=',') {
        $start = microtime(true);

        $fp = fopen($path, 'r');
        $this->countRows = -1;
        $keys = [];
        $keysHasKey = [];
        while (($data = fgetcsv($fp, 0, $separator)) !== FALSE) {
            if ($this->countRows < 0) {
                $keys = $data;
                foreach ($keys as $k => $v)
                    $keysHasKey[$v] = trim($k);
            } else {
                $elem = [];
                foreach ($data as $k => $v)
                    $elem[$keys[$k]] = trim($v);
                $this->parseData($elem);
            }
            $this->countRows ++;
        }
        fclose($fp);

        $this->timeLoad = round(microtime(true)-$start, 5);
        return $this->list;
    }

    public function getFieldMapped($field, $value) {
        $type = $this->DB_fields_mapping[$field];
        return ($type=='string')?'"'.str_replace('"', '', $value).'"':((empty($value) && $value!==0)?'null':$value*1);
    }

    public function export($fetch=true) {
        global $DB;
        $this->DB_fields_mapping;
        $start = microtime(true);
        $startBis = microtime(true);
        $headSql = "INSERT INTO $this->table (".implode(', ', array_keys($this->DB_fields_mapping)).") VALUES ";
        $rowsSql = []; $count = 0;
        foreach ($this->list as $k => $elem) {
            $elem = $this->parseDateForInsert($elem);
            $fieldsForInsert = [];
            foreach ($this->DB_fields_mapping as $key => $type)
                $fieldsForInsert[$key] = ($type=='string')?'"'.str_replace('"', '', $elem[$key]).'"':((empty($elem[$key]) && $elem[$key]!==0)?'null':$elem[$key]*1);
            $rowsSql[] = str_replace('""', "NULL", '('.implode(', ', $fieldsForInsert).')');
            $count++;
            if ($count >= 1000) {
                // var_dump($headSql . implode(", ", $rowsSql));
                $DB->exec($headSql . implode(", ", $rowsSql));
                echo "  import $this->table: ".$count." en ".$this->timeInsert."s<br>\n";
                $rowsSql = []; $count = 0;
            }
        }
        if ($count) {
            // var_dump($headSql . implode(", ", $rowsSql));
            $DB->exec($headSql . implode(", ", $rowsSql));
            $tBis = round(microtime(true)-$start, 5);
            echo "  import $this->table: ".$count." en ".$tBis."s<br>\n";
            $startBis = microtime(true);
        }
        $this->timeInsert = round(microtime(true)-$start, 5);
        // Fetch data !!
        if ($fetch)
            $this->fetchDataDB();
    }

    public function update($fields, $pk="pk") {
        global $DB;
        // Update
        $start = microtime(true);
        $rowsSql = []; $count = 0;
        foreach ($this->list as $elem) {
            $update = [];
            foreach ($fields as $field)
                $update[$field] = $field .'='. $this->getFieldMapped($field, $elem[$field]);
            $update = "UPDATE $this->table SET ".implode(', ', $update)." WHERE $pk = ".$elem['pk'];
            $rowsSql[] = str_replace('""', "NULL", $update);
            $count++;
            if ($count >= 1000) {
                $DB->exec(implode("; ", $rowsSql));
                echo "  update $this->table: ".$count." <br>\n";
                $rowsSql = []; $count = 0;
            }
        }
        if ($count) {
            $DB->exec(implode("; ", $rowsSql));
            echo "  update $this->table: ".$count." <br>\n";
        }
        $this->timeUpdate = round(microtime(true)-$start, 2);
        echo "    en $this->timeUpdate s <br>\n";
    }

    public function fetchDataDB() {
        if (empty($this->CurGtfsCtrl->AgencyCtrl))
            return [];
        global $DB;
        $res = $DB->query("SELECT * FROM $this->table WHERE agency_pk = ".$this->CurGtfsCtrl->AgencyCtrl->curAgencyPk);
        $this->list = [];
        foreach ($res as $val) {
            $this->list[$val[$this->primaryFieldKeyList]] = $this->mapValue($val);
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
        return $hourMin[0]*60*60 + $hourMin[1]*60 + (!empty($hourMin[2])?$hourMin[2]:0);
    }
    public static function yyyymmddToIsoDate($yyyymmdd) {
        if (strpos($yyyymmdd, '-') !==-1)
            return $yyyymmdd;
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

    public function getPk($id) {
        if (isset($this->list[$id]) && isset($this->list[$id]['pk']))
            return $this->list[$id]['pk'];
        else
            return null;
    }
}