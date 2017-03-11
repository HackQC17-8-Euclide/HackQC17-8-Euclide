<?php

namespace HackQC17_8_Euclide\GFTS;

class GtfsRouteController extends GtfsElemController {
    public $spatialExtent = null;
    public function parseData($elem) {
    	if (empty($elem['route_id']))
    		throw new \Exception("route_id empty ! ".json_encode($elem), 1);
        // route_id,agency_id,route_short_name,route_long_name,route_desc,route_type,route_url,route_color,route_text_color
        // Route type:
		    // 0: Tram, Streetcar, Light rail. Any light rail or street level system within a metropolitan area.
		    // 1: Subway, Metro. Any underground rail system within a metropolitan area.
		    // 2: Rail. Used for intercity or long-distance travel.
		    // 3: Bus. Used for short- and long-distance bus routes.
		    // 4: Ferry. Used for short- and long-distance boat service.
		    // 5: Cable car. Used for street-level cable cars where the cable runs beneath the car.
		    // 6: Gondola, Suspended cable car. Typically used for aerial cable cars where the car is suspended from the cable.
		    // 7: Funicular. Any rail system designed for steep inclines.

        $this->list[$elem['route_id']] = [
        	'route_id' => $elem['route_id'],
			'agency_id' => !isset($elem['agency_id'])?null:$elem['agency_id'],
			'route_short_name' => $elem['route_short_name'],
			'route_long_name' => $elem['route_long_name'],
			'route_desc' => !isset($elem['route_desc'])?null:$elem['route_desc'],
			'route_type' => !isset($elem['route_type'])?3:$elem['route_type'],
			'route_url' => !isset($elem['route_url'])?null:$elem['route_url'],
			'route_color' => !isset($elem['route_color'])?null:$elem['route_color'],
			'route_text_color' => !isset($elem['route_text_color'])?null:$elem['route_text_color']
        ];
    }
}