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

    public static function fetchDateServices($date, $agency_pk=null) {
        global $DB;
        $whereAgency = empty($agency_pk)?'':'c.agency_pk = '.($agency_pk*1).' AND ';
        $services = [];
        // $sql = "SELECT *
        $sql = "SELECT DISTINCT c.service_id
                FROM gtfs_calendar_dates cd
                    LEFT JOIN gtfs_calendar c ON c.service_id = cd.service_id AND cd.date >= c.start_date and cd.date <= c.end_date
                WHERE $whereAgency cd.date = :curDatetime AND cd.exception_type = 1";
        $res = $DB->query($sql, ['curDatetime' => $date]);
        foreach ($res as $v)
            $services[$v['service_id']] = 1;
        $sql = "SELECT DISTINCT c.service_id
                FROM gtfs_calendar c
                    LEFT JOIN gtfs_calendar_dates cd ON c.service_id = cd.service_id AND cd.date = ':curDatetime'
                where $whereAgency (
                    cd.date = :curDatetime AND cd.exception_type = 1
                    OR (
                        cd.date = :curDatetime AND cd.exception_type != 2
                        OR cd.date is null AND :curDatetime >= c.start_date and :curDatetime <= c.end_date

                    ) AND (
                        WEEKDAY(:curDatetime)=0 AND c.monday = 1
                        OR   WEEKDAY(:curDatetime)=1 AND c.tuesday = 1
                        OR   WEEKDAY(:curDatetime)=2 AND c.wednesday = 1
                        OR   WEEKDAY(:curDatetime)=3 AND c.thursday = 1
                        OR   WEEKDAY(:curDatetime)=4 AND c.friday = 1
                        OR   WEEKDAY(:curDatetime)=5 AND c.saturday = 1
                        OR   WEEKDAY(:curDatetime)=6 AND c.sunday = 1
                    )
                )";
        $res = $DB->query($sql, ['curDatetime' => $date]);
        foreach ($res as $v)
            $services[$v['service_id']] = 1;
        return array_keys($services);
    }
}