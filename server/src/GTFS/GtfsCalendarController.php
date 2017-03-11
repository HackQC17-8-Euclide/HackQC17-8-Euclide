<?php

namespace HackQC17_8_Euclide\GFTS;

class GtfsCalendarController extends GtfsElemController {
    public function parseData($elem) {
        $this->list[$elem['service_id']] = [
        	'service_id' => $elem['service_id'],
			'monday' => $elem['monday']*1,
			'tuesday' => $elem['tuesday']*1,
			'wednesday' => $elem['wednesday']*1,
			'thursday' => $elem['thursday']*1,
			'friday' => $elem['friday']*1,
			'saturday' => $elem['saturday']*1,
			'sunday' => $elem['sunday']*1,
			'start_date' => self::yyyymmddToIsoDate($elem['start_date']),
			'end_date' => self::yyyymmddToIsoDate($elem['end_date'])
        ];
    }
}