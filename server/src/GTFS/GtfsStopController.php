<?php

namespace HackQC17_8_Euclide\GFTS;

class GtfsStopController extends GtfsElemController {
    public $spatialExtent = null;
    public $listFromCoords = [];

    function __construct($path, $CurGtfsCtrl) {
        global $DB;
        $this->table = 'gtfs_stop';
        $this->DB_fields_mapping = [
            'stop_id' => 'string',
            'stop_code' => 'string',
            'stop_name' => 'string',
            'stop_lat' => 'int',
            'stop_lon' => 'int',
            'stop_desc' => 'string',
            'parent_station' => 'string',
            'agency_pk' => 'int'
        ];
        parent::__construct($path, $CurGtfsCtrl);
        $this->primaryFieldKeyList = 'stop_id';
    }

    public function parseData($elem) {
        if (!isset($elem['stop_id']))
        	throw new \Exception("Pas de stop_id ! ".json_encode($elem), 1);
        if (!isset($elem['stop_name']))
        	throw new \Exception("Pas de stop_name ! ".json_encode($elem), 1);
        $elem = [
        	'stop_id' => $elem['stop_id'],
			'stop_code' => !isset($elem['stop_code'])?$elem['stop_id']:$elem['stop_code'],
			'stop_name' => $elem['stop_name'],
			'stop_lat' => $elem['stop_lat']*1,
			'stop_lon' => $elem['stop_lon']*1,
			'stop_desc' => empty($elem['stop_desc'])?'':$elem['stop_desc'],
			'parent_station' => empty($elem['parent_station'])?null:''
        ];
        $this->updateSpatialExtent($this->spatialExtent, [$elem['stop_lon'], $elem['stop_lat']]);
        $this->list[$elem['stop_id']] = $elem;
        $this->listFromCoords[$elem['stop_lon'].'_'.$elem['stop_lat']] = $elem;
    }

    public function parseDateForInsert($elem) {
        return [
            'stop_id' => $elem['stop_id'],
            'stop_code' => $elem['stop_code'],
            'stop_name' => $elem['stop_name'],
            'stop_lat' => $elem['stop_lat'],
            'stop_lon' => $elem['stop_lon'],
            'stop_desc' => $elem['stop_desc'],
            'parent_station' => $elem['parent_station'],
            'agency_pk' => $this->CurGtfsCtrl->AgencyCtrl->curAgencyPk
        ];
    }

    public function fetchStopIdFromCoords($coord) {
        if (isset($this->listFromCoords[$coord[0].'_'.$coord[1]]))
            return $this->listFromCoords[$coord[0].'_'.$coord[1]];
        else
            return null;
    }
}