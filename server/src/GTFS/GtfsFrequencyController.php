<?php

namespace HackQC17_8_Euclide\GFTS;

class GtfsFrenquencyController extends GtfsElemController {
    function __construct($path, $CurGtfsCtrl, $options=[]) {
        global $DB;
        $this->table = 'gtfs_frequency';
        $this->DB_fields_mapping = [
            'agency_pk' => 'int',
            'trip_pk' => 'int',
            'start_time' => 'string',
            'end_time' => 'string',
            'headway_secs' => 'int'
        ];
        parent::__construct($path, $CurGtfsCtrl, $options);
        $this->primaryFieldKeyList = 'pk';
    }

    public function parseData($elem) {
        if (!isset($this->CurGtfsCtrl->TripCtrl->list[$elem['trip_id']]))
            throw new \Exception("Trip inconnu! ".json_encode($elem), 1);
        $trip = [
            'trip_id' => $elem['trip_id'],
            'start_time' => $elem['start_time'],
            'end_time' => $elem['end_time'],
            'headway_secs' => $elem['headway_secs']
        ];
        $this->list[] = $trip;
    }
    public function parseDateForInsert($elem) {
        return [
            'trip_pk' => $this->CurGtfsCtrl->TripCtrl->getPk($elem['trip_id']),
            'agency_pk' => $this->CurGtfsCtrl->AgencyCtrl->curAgencyPk,
            'start_time' => $elem['start_time'],
            'end_time' => $elem['end_time'],
            'headway_secs' => $elem['headway_secs']
        ];
    }
}
