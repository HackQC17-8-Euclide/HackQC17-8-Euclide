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

// SELECT date(start_date) date_start,
//     COUNT(*) count_day,
//     count(case when hour(start_date) >= 15 AND hour(start_date) < 19 then 1 else null end) count_afternoon,
//     count(case when hour(start_date) >= 19 AND hour(start_date) <= 23 then 1 else null end) count_evening
// FROM `bixi_od`
// WHERE
//     start_stop IN ('6021', '6003', '6738', '6202', '7066', '7067', '7069', '6007', '6008', '6203', '6009', '6901', '7074', '6012', '6013', '6209', '7077', '6014', '6015', '6018', '6214', '6019', '6020', '6413', '6180', '6073', '6078', '6081', '6034', '6083', '6729')
// GROUP BY date(start_date)

// SELECT date(end_date) date_end,
//     COUNT(*) count_day,
//     count(case when hour(end_date) >= 15 AND hour(end_date) < 19 then 1 else null end) count_afternoon,
//     count(case when hour(end_date) >= 19 AND hour(end_date) <= 23 then 1 else null end) count_evening
// FROM `bixi_od`
// WHERE
//     end_stop IN ('6021', '6003', '6738', '6202', '7066', '7067', '7069', '6007', '6008', '6203', '6009', '6901', '7074', '6012', '6013', '6209', '7077', '6014', '6015', '6018', '6214', '6019', '6020', '6413', '6180', '6073', '6078', '6081', '6034', '6083', '6729')
// GROUP BY date(end_date)