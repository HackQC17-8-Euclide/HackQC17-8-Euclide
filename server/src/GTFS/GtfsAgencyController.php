<?php

namespace HackQC17_8_Euclide\GFTS;

class GtfsAgencyController extends GtfsElemController {
    function __construct($path, $CurGtfsCtrl) {
        global $DB;
        $this->table = 'gtfs_agency';
        $this->DB_fields_mapping = [
            'agency_id' => 'string',
            'agency_name' => 'string',
            'agency_url' => 'string',
            'agency_timezone' => 'string',
            'agency_lang' => 'string',
            'agency_phone' => 'string',
            'agency_fare_url' => 'string',
            'agency_email' => 'string',
            'lng_min' => 'int',
            'lng_max' => 'int',
            'lat_min' => 'int',
            'lat_max' => 'int'
        ];
    	parent::__construct($path, $CurGtfsCtrl);
    	if ($this->count == 0)
    		throw new \Exception("No agency found !!", 1);
    	if ($this->count != 1)
    		throw new \Exception("Can't yet deal with more than one agency", 1);
        $this->curAgencyId = current($this->list);
        $this->curAgencyId = $this->curAgencyId['agency_id'];
        $this->curAgencyPk = current($DB->queryFirst("SELECT pk FROM $this->table WHERE agency_id = '".$this->curAgencyId."'"));
    }

    public function fetchDataDB() {
        global $DB;
        $res = $DB->queryFirst("SELECT * FROM $this->table WHERE agency_id = '$this->curAgencyId'");
        $this->curAgencyPk = $res['pk'];
        $this->list = [];
        $this->list[$res['agency_id']] = $this->mapValue($res);
    }
    public function parseData($elem) {
    	// agency_id,agency_name,agency_url,agency_timezone,agency_lang,agency_phone
        if (!isset($elem['agency_id']))
            throw new \Exception("agency_id inconnue pour ".json_encode($elem), 1);
        $elem['agency_name'] = empty($elem['agency_name'])?$elem['agency_id']:$elem['agency_name'];
        $this->list[$elem['agency_id']] = [
            'agency_id' => $elem['agency_id'],
            'agency_name' => $elem['agency_name'],
            'agency_url' => !isset($elem['agency_url'])?null:$elem['agency_url'],
            'agency_timezone' => !isset($elem['agency_timezone'])?null:$elem['agency_timezone'],
            'agency_lang' => !isset($elem['agency_lang'])?null:$elem['agency_lang'],
            'agency_phone' => !isset($elem['agency_phone'])?null:$elem['agency_phone'],
            'agency_fare_url' => !isset($elem['agency_fare_url'])?null:$elem['agency_fare_url'],
            'agency_email' => !isset($elem['agency_email'])?null:$elem['agency_email'],
            'spatialExtent' => null
        ];
    }

    public function parseDateForInsert($elem) {
        return [
            'agency_id' => $elem['agency_id'],
            'agency_name' => $elem['agency_name'],
            'agency_url' => $elem['agency_url'],
            'agency_timezone' => $elem['agency_timezone'],
            'agency_lang' => $elem['agency_lang'],
            'agency_phone' => $elem['agency_phone'],
            'agency_fare_url' => $elem['agency_fare_url'],
            'agency_email' => $elem['agency_email'],
            'lng_min' => empty($elem['spatialExtent'])? null : $elem['spatialExtent']['lng'][0],
            'lng_max' => empty($elem['spatialExtent'])? null : $elem['spatialExtent']['lng'][1],
            'lat_min' => empty($elem['spatialExtent'])? null : $elem['spatialExtent']['lat'][0],
            'lat_max' => empty($elem['spatialExtent'])? null : $elem['spatialExtent']['lat'][1]
        ];
    }
}