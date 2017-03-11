<?php

namespace HackQC17_8_Euclide\GFTS;

class GtfsStopTimeController extends GtfsElemController {
    public $spatialExtent = null;
    public function parseData($elem) {
        // trip_id, arrival_time, departure_time, stop_id, stop_sequence, stop_headsign, pickup_type, drop_off_type, shape_dist_traveled
        if (!empty($this->CurGtfsCtrl->StopCtrl) && !isset($this->CurGtfsCtrl->StopCtrl->list[$elem['stop_id']]))
            throw new \Exception("Stop inconnu pour le trip ".json_encode($elem), 1);
        if (!isset($this->list[$elem['trip_id']])) {
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
            'stop_headsign' => $elem['stop_headsign'],
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

        $this->list[$elem['trip_id']]['stops'][$stopTime['stop_sequence']] = $elem;
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
    }
}


