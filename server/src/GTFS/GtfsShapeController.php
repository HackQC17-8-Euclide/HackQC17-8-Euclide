<?php

namespace HackQC17_8_Euclide\GFTS;

class GtfsShapeController extends GtfsElemController {
    function __construct($path, $CurGtfsCtrl, $options=[]) {
        global $DB;
        $this->table = 'gtfs_shape';
        $this->DB_fields_mapping = [
            'shape_id' => 'string',
            'agency_pk' => 'int',
            'route_pk' => 'int',
        ];
        parent::__construct($path, $CurGtfsCtrl, $options);
        $this->primaryFieldKeyList = 'shape_id';
    }
    public function parseData($elem) {
        if (!isset($this->list[$elem['shape_id']]))
            $this->list[$elem['shape_id']] = [
                'shape_id' => $elem['shape_id'],
                'route_id' => null,
                'stops' => []
            ];
        $stop = [
            'shape_pt_sequence' => $elem['shape_pt_sequence']*1,
            'shape_dist_traveled' => !isset($elem['shape_dist_traveled'])?null:$elem['shape_dist_traveled']*1,
            'shape_pt_lon' => $elem['shape_pt_lon']*1,
            'shape_pt_lat' => $elem['shape_pt_lat']*1,
            'stop_id' => null
        ];
        // On laisse tomber, ya pas de match entre la shape & un arrêt ...
        // if (!empty($this->CurGtfsCtrl->StopCtrl))
        //     $stop['stop_id'] = $this->CurGtfsCtrl->StopCtrl->fetchStopIdFromCoords([$stop['shape_pt_lon'], $stop['shape_pt_lat']]);
        $this->list[$elem['shape_id']]['stops'][$elem['shape_pt_sequence']] = $stop;
    }
    public function parseDateForInsert($elem) {
        return [
            'shape_id' => $elem['shape_id'],
            'route_pk' => $this->CurGtfsCtrl->RouteCtrl->getPk($elem['route_id']),
            'agency_pk' => $this->CurGtfsCtrl->AgencyCtrl->curAgencyPk
        ];
    }
}