<?php

namespace HackQC17_8_Euclide\GFTS;

class GtfsAgencyController extends GtfsElemController {
    function __construct($path, $CurGtfsCtrl) {
    	parent::__construct($path, $CurGtfsCtrl);
    	if ($this->count == 0)
    		throw new \Exception("No agency found !!", 1);
    	if ($this->count != 1)
    		throw new \Exception("Can't yet deal with more than one agency", 1);

    }

    public function parseData($elem) {
    	// agency_id,agency_name,agency_url,agency_timezone,agency_lang,agency_phone
        if (!isset($elem['agency_id']))
            throw new \Exception("agency_id inconnue pour ".json_encode($elem), 1);
        $elem['agency_name'] = empty($elem['agency_name'])?$elem['agency_id']:'';
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
}