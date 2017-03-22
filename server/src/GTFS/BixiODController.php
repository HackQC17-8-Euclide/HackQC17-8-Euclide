<?php

namespace HackQC17_8_Euclide\GFTS;

class BixiODController extends GtfsElemController {
    public $spatialExtent = null;
    public $listFromCoords = [];

    function __construct($path, $CurGtfsCtrl, $options=[]) {
        global $DB;
        $this->table = 'bixi_od';
        $this->DB_fields_mapping = [
            'start_stop' => 'string',
            'start_date' => 'string',
            'end_stop' => 'string',
            'end_date' => 'string',
            'duration' => 'int',
            'is_member' => 'int',
            'gender' => 'string',
            'language' => 'string'
        ];
        parent::__construct($path, $CurGtfsCtrl, $options);
        $this->primaryFieldKeyList = 'stop_id';
    }

    public function parseData($elem) {
        $elem = [
            'start_stop' => $elem['Start station'],
            'start_date' => $elem['Start date'],
            'end_stop' => $elem['End station'],
            'end_date' => $elem['End date'],
            'duration' => $elem['Total duration (s)'],
            'is_member' => $elem['isMember'],
            "gender" => $elem["Member's gender"],
            "language" => $elem["Member's language"]
        ];
        $this->list[] = $elem;
    }

    public function parseDateForInsert($elem) {
        return [
            'start_stop' => $elem['start_stop'],
            'start_date' => $elem['start_date'],
            'end_stop' => $elem['end_stop'],
            'end_date' => $elem['end_date'],
            'duration' => $elem['duration'],
            'is_member' => $elem['is_member'],
            'gender' => $elem['gender'],
            'language' => $elem['language']
        ];
    }
}