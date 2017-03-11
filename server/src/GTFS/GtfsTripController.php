<?php

namespace HackQC17_8_Euclide\GFTS;

class GtfsTripController extends GtfsElemController {
    public $spatialExtent = null;
    public function parseData($elem) {
        if (!isset($this->CurGtfsCtrl->CalendarCtrl->list[$elem['service_id']]) && !isset($this->CurGtfsCtrl->CalendarDateCtrl->list[$elem['service_id']]))
            throw new \Exception("Service inconnu! ".json_encode($elem), 1);
        if (isset($elem['shape_id']) && !empty($elem['shape_id']) && !isset($this->CurGtfsCtrl->ShapeCtrl->list[$elem['shape_id']]))
            throw new \Exception("shape_id inconnue! ".json_encode($elem), 1);
        $trip = [
            'route_id' => $elem['route_id'],
            'service_id' => $elem['service_id'],
            'trip_id' => $elem['trip_id'],
            'trip_headsign' => !isset($elem['trip_headsign'])?null:$elem['trip_headsign'],
            'trip_short_name' => !isset($elem['trip_short_name'])?null:$elem['trip_short_name'],
            'direction_id' => !isset($elem['direction_id'])?null:$elem['direction_id'],
            'block_id' => !isset($elem['block_id'])?null:$elem['block_id'],
            'shape_id' => !isset($elem['shape_id'])?null:$elem['shape_id'],
            'wheelchair_accessible' => !isset($elem['wheelchair_accessible'])?null:$elem['wheelchair_accessible']*1,
            'bikes_allowed' => !isset($elem['bikes_allowed'])?null:$elem['bikes_allowed'],
            'departure_time' => null,
            'arrival_time' => null,
            'departure_sec' => null,
            'arrival_sec' => null
        ];
        if (!empty($trip['shape_id']) && !empty($this->CurGtfsCtrl->ShapeCtrl)) {
            if (!isset($this->CurGtfsCtrl->ShapeCtrl->list[$trip['shape_id']])) { // ben ils ont oublié d'ajouter un shape dans le GTFS...
                $this->CurGtfsCtrl->ShapeCtrl->list[$trip['shape_id']] = [
                    'shape_id' => $trip['shape_id'],
                    'stops' => []
                ];
            }
            if (!empty($this->CurGtfsCtrl->ShapeCtrl->list[$trip['shape_id']]['route_id']) && $this->CurGtfsCtrl->ShapeCtrl->list[$trip['shape_id']]['route_id'] != $trip['route_id'])
                echo "On a plusieurs route (".$this->CurGtfsCtrl->ShapeCtrl->list[$trip['shape_id']]['route_id'].", ".$trip['route_id'].") sur un shape ".$trip['shape_id']."<br>";
            $this->CurGtfsCtrl->ShapeCtrl->list[$trip['shape_id']]['route_id'] = $trip['route_id'];
        }
        $this->list[$trip['trip_id']] = $trip;
    }
}
