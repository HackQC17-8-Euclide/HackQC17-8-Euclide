<?php

namespace HackQC17_8_Euclide\GFTS;

class GtfsCalendarController extends GtfsElemController {
    function __construct($path, $CurGtfsCtrl, $options=[]) {
        global $DB;
        $this->table = 'gtfs_calendar';
        $this->DB_fields_mapping = [
            'service_id' => 'string',
            'monday' => 'int',
            'tuesday' => 'int',
            'wednesday' => 'int',
            'thursday' => 'int',
            'friday' => 'int',
            'saturday' => 'int',
            'sunday' => 'int',
            'start_date' => 'string',
            'end_date' => 'string',
            'agency_pk' => 'int'
        ];
        parent::__construct($path, $CurGtfsCtrl, $options);
        $this->primaryFieldKeyList = 'service_id';
    }

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
    public function parseDateForInsert($elem) {
        return [
            'service_id' => $elem['service_id'],
            'monday' => $elem['monday'],
            'tuesday' => $elem['tuesday'],
            'wednesday' => $elem['wednesday'],
            'thursday' => $elem['thursday'],
            'friday' => $elem['friday'],
            'saturday' => $elem['saturday'],
            'sunday' => $elem['sunday'],
            'start_date' => $elem['start_date'],
            'end_date' => $elem['end_date'],
            'agency_pk' => $this->CurGtfsCtrl->AgencyCtrl->curAgencyPk
        ];
    }
}