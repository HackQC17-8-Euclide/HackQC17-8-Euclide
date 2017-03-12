<?php

namespace HackQC17_8_Euclide\GFTS;

class GtfsCalendarDateController extends GtfsElemController {
    public $listServiceId = [];

    function __construct($path, $CurGtfsCtrl, $options=[]) {
        $this->table = 'gtfs_calendar_dates';
        $this->DB_fields_mapping = [
            'service_id' => 'string',
            'date' => 'string',
            'exception_type' => 'int',
            'agency_pk' => 'int'
        ];
        parent::__construct($path, $CurGtfsCtrl, $options);
        $this->primaryFieldKeyList = 'service_id';
        foreach ($this->list as $service_id => $service) {
            if (!empty($this->CurGtfsCtrl->CalendarCtrl) && !isset($this->CurGtfsCtrl->CalendarCtrl->list[$service['service_id']])) {}
                $this->CurGtfsCtrl->CalendarCtrl->list[$service['service_id']] = [
                    'service_id' => $service['service_id'],
                    'monday' => 0, 'tuesday' => 0, 'wednesday' => 0, 'thursday' => 0, 'friday' => 0, 'saturday' => 0, 'sunday' => 0,
                    'start_date' => date("Y-m-d", $service['start_date']),
                    'end_date' => date("Y-m-d", $service['end_date'])
                ];
        }
    }

    public function parseData($elem) {
        if (!isset($this->list[$elem['service_id']])) {
            $this->list[$elem['service_id']] = [
                'service_id' => $elem['service_id'],
                'start_date' => null,
                'end_date' => null,
                'exeptions' => []
            ];
        }
        $elem['date'] = self::yyyymmddToIsoDate($elem['date']);
        $serviceException = [
            'date' => $elem['date'],
            'dateTimestamp' => strtotime($elem['date']),
            'exception_type' => $elem['exception_type']*1 // 1 : added, 2: removed
        ];
        $this->list[$elem['service_id']]['exeptions'][$elem['date']] = $serviceException;
        $this->majServiceRangeDates($this->list[$elem['service_id']], $serviceException);
    }

    public function export($fetch=true) {
        global $DB;
        $this->DB_fields_mapping;
        $start = microtime(true);
        $headSql = "INSERT INTO $this->table (".implode(', ', array_keys($this->DB_fields_mapping)).") VALUES ";
        $rowsSql = []; $count = 0;
        foreach ($this->list as $elements) {
            foreach ($elements['exeptions'] as $elem) {
                $elem = [
                    'service_id' => $elements['service_id'],
                    'date' => $elem['date'],
                    'exception_type' => $elem['exception_type'],
                    'agency_pk' => $this->CurGtfsCtrl->AgencyCtrl->curAgencyPk
                ];
                $fieldsForInsert = [];
                foreach ($this->DB_fields_mapping as $key => $type)
                    $fieldsForInsert[$key] = ($type=='string')?'"'.str_replace('"', '', $elem[$key]).'"':((empty($elem[$key]) && $elem[$key]!==0)?'null':$elem[$key]*1);
                $rowsSql[] = str_replace('""', "NULL", '('.implode(', ', $fieldsForInsert).')');
                $count++;
            }
        }
        if ($count) {
            // var_dump($headSql . implode(", ", $rowsSql));
            $DB->exec($headSql . implode(", ", $rowsSql));
            $this->timeInsert = round(microtime(true)-$start, 2);
            echo "  import $this->table: ".$count." en ".$this->timeInsert."s<br>\n";
        }
        // Fetch data !!
        if ($fetch)
            $this->fetchDataDB();
    }
    public function fetchDataDB() {
        global $DB;
        $res = $DB->query("SELECT * FROM $this->table");
        $this->list = [];
        foreach ($res as $elem) {
            if (!isset($this->list[$elem['service_id']])) {
                $this->list[$elem['service_id']] = [
                    'service_id' => $elem['service_id'],
                    'start_date' => null,
                    'end_date' => null,
                    'exeptions' => []
                ];
            }
            $serviceException = [
                'pk' => $elem['pk'],
                'date' => $elem['date'],
                'dateTimestamp' => strtotime($elem['date']),
                'exception_type' => $elem['exception_type']*1 // 1 : added, 2: removed
            ];
            $this->list[$elem['service_id']]['exeptions'][$elem['date']] = $serviceException;
            $this->majServiceRangeDates($this->list[$elem['service_id']], $serviceException);
        }
    }

    public function majServiceRangeDates(&$listElem, $elem) {
        if (empty($listElem['start_date'])) {
            $listElem['start_date'] = $elem['dateTimestamp'];
            $listElem['end_date'] = $elem['dateTimestamp'];
        }
        if ($listElem['start_date'] > $elem['dateTimestamp'])
            $listElem['start_date'] = $elem['dateTimestamp'];
        if ($listElem['end_date'] < $elem['dateTimestamp'])
            $listElem['end_date'] = $elem['dateTimestamp'];
    }
}