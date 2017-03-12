<?php

namespace HackQC17_8_Euclide\GFTS;

class GtfsStopTimeController extends GtfsElemController {

    function __construct($path, $CurGtfsCtrl, $options=[]) {
        global $DB;
        $this->table = 'gtfs_stop_times';
        $this->DB_fields_mapping = [
            'trip_pk' => 'int',
            'stop_pk' => 'int',
            'stop_sequence' => 'int',
            'stop_headsign' => 'string',
            'arrival_time' => 'string',
            'departure_time' => 'string',
            'arrival_sec' => 'int',
            'departure_sec' => 'int',
            'shape_dist_traveled' => 'int',
            'drop_off_type' => 'string',
            'pickup_type' => 'string',
            'agency_pk' => 'int'
        ];
        parent::__construct($path, $CurGtfsCtrl, $options);
        if (count($this->list) == 20 && !empty($this->options['autoExport']))
            $this->export(false);
        $this->primaryFieldKeyList = 'trip_id';
    }

    public function parseData($elem) {
        // trip_id, arrival_time, departure_time, stop_id, stop_sequence, stop_headsign, pickup_type, drop_off_type, shape_dist_traveled
        if (!empty($this->CurGtfsCtrl->StopCtrl) && !isset($this->CurGtfsCtrl->StopCtrl->list[$elem['stop_id']]))
            throw new \Exception("Stop inconnu pour le trip ".json_encode($elem), 1);
        if (!isset($this->list[$elem['trip_id']])) {
            if (count($this->list) == 20 && !empty($this->options['autoExport'])) {
                $this->export(false);
                unset($this->list);
                $this->list = [];
            }
            $this->list[$elem['trip_id']] = [
                'trip_id' => $elem['trip_id'],
                'departure_time' => null,
                'arrival_time' => null,
                'departure_sec' => null,
                'arrival_sec' => null,
                'stops' => []
            ];
        }
        $stopTime = [
            'stop_id' => $elem['stop_id'],
            'stop_sequence' => $elem['stop_sequence']*1,
            'stop_headsign' => isset($elem['stop_headsign'])?$elem['stop_headsign']:null,
            'arrival_time' => $elem['arrival_time'],
            'departure_time' => $elem['departure_time'],
            'arrival_sec' => self::hourMinToSec($elem['arrival_time']),
            'departure_sec' => self::hourMinToSec($elem['departure_time']),
            'shape_dist_traveled' => !isset($elem['shape_dist_traveled'])?null:$elem['shape_dist_traveled']*1,
            'drop_off_type' => isset($elem['drop_off_type'])?$elem['drop_off_type']*1:null,
            'pickup_type' => isset($elem['pickup_type'])?$elem['pickup_type']*1:null
        ];
        $this->majTripTimes($this->list[$elem['trip_id']], $stopTime);
        // On laisse tomber, ya pas de match entre la shape & un arrêt ...

        $this->list[$elem['trip_id']]['stops'][$stopTime['stop_sequence']] = $stopTime;
    }

    public function export($fetch=true) {
        global $DB;
        $this->DB_fields_mapping;
        $start = microtime(true);
        $headSql = "INSERT INTO $this->table (".implode(', ', array_keys($this->DB_fields_mapping)).") VALUES ";
        $rowsSql = []; $count = 0;
        foreach ($this->list as $elements) {
            foreach ($elements['stops'] as $elem) {
                $elem = [
                    'trip_pk' => $this->CurGtfsCtrl->TripCtrl->getPk($elements['trip_id']),
                    'stop_pk' => $this->CurGtfsCtrl->StopCtrl->getPk($elem['stop_id']),
                    'stop_sequence' => $elem['stop_sequence'],
                    'stop_headsign' => $elem['stop_headsign'],
                    'arrival_time' => $elem['arrival_time'],
                    'departure_time' => $elem['departure_time'],
                    'arrival_sec' => $elem['arrival_sec'],
                    'departure_sec' => $elem['departure_sec'],
                    'shape_dist_traveled' => $elem['shape_dist_traveled'],
                    'drop_off_type' => $elem['drop_off_type'],
                    'pickup_type' => $elem['pickup_type'],
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
        // if ($fetch)
        //     $this->fetchDataDB();
    }

    public function majTripTimes(&$trip, $stopTime) {
        if (empty($trip['departure_sec'])) {
            $trip['departure_time'] = $stopTime['departure_time'];
            $trip['arrival_time'] = $stopTime['arrival_time'];
            $trip['departure_sec'] = $stopTime['departure_sec'];
            $trip['arrival_sec'] = $stopTime['arrival_sec'];
        }
        if ($trip['departure_sec'] > $stopTime['arrival_sec']) {
            $trip['departure_sec'] = $stopTime['arrival_sec'];
            $trip['departure_time'] = $stopTime['departure_time'];
        }
        if ($trip['arrival_sec'] < $stopTime['departure_sec']) {
            $trip['arrival_sec'] = $stopTime['departure_sec'];
            $trip['arrival_time'] = $stopTime['departure_time'];
        }
        if (empty($this->CurGtfsCtrl->TripCtrl->list[$trip['trip_id']]['departure_sec'])) {
            $this->CurGtfsCtrl->TripCtrl->list[$trip['trip_id']]['departure_sec'] = $stopTime['arrival_sec'];
            $this->CurGtfsCtrl->TripCtrl->list[$trip['trip_id']]['departure_time'] = $stopTime['departure_time'];
            $this->CurGtfsCtrl->TripCtrl->list[$trip['trip_id']]['arrival_sec'] = $stopTime['departure_sec'];
            $this->CurGtfsCtrl->TripCtrl->list[$trip['trip_id']]['arrival_time'] = $stopTime['departure_time'];
        }
        if ($this->CurGtfsCtrl->TripCtrl->list[$trip['trip_id']]['departure_sec'] > $stopTime['arrival_sec']) {
            $this->CurGtfsCtrl->TripCtrl->list[$trip['trip_id']]['departure_sec'] = $stopTime['arrival_sec'];
            $this->CurGtfsCtrl->TripCtrl->list[$trip['trip_id']]['departure_time'] = $stopTime['departure_time'];
        }
        if ($this->CurGtfsCtrl->TripCtrl->list[$trip['trip_id']]['arrival_sec'] < $stopTime['departure_sec']) {
            $this->CurGtfsCtrl->TripCtrl->list[$trip['trip_id']]['arrival_sec'] = $stopTime['departure_sec'];
            $this->CurGtfsCtrl->TripCtrl->list[$trip['trip_id']]['arrival_time'] = $stopTime['departure_time'];
        }
    }
}


