<?php

namespace HackQC17_8_Euclide\GFTS;

class GtfsCalendarDateController extends GtfsElemController {
    public $listServiceId = [];

    function __construct($path, $CurGtfsCtrl) {
        parent::__construct($path, $CurGtfsCtrl);
        foreach ($this->list as $service_id => $service) {
            if (!empty($this->CurGtfsCtrl->CalendarCtrl) && !isset($this->CurGtfsCtrl->CalendarCtrl->list[$service['service_id']]))
                $this->CurGtfsCtrl->CalendarCtrl->list[$service['service_id']][] = [
                    'service_id' => $service['service_id'],
                    'monday' => 0, 'tuesday' => 0, 'wednesday' => 0, 'thursday' => 0, 'friday' => 0, 'saturday' => 0, 'sunday' => 0,
                    'start_date' => $service['start_date'],
                    'end_date' => $service['end_date']
                ];
        }
    }

    public function parseData($elem) {
        if (!isset($this->list[$elem['service_id']])) {
            $this->list[$elem['service_id']] = [
                'service_id' => $elem['service_id'],
                'start_date' => null,
                'end_date' => null,
                'exeptions' => []
            ];
        }
        $elem['date'] = self::yyyymmddToIsoDate($elem['date']);
        $serviceException = [
            'date' => $elem['date'],
            'dateTimestamp' => strtotime($elem['date']),
            'exception_type' => $elem['exception_type']*1 // 1 : added, 2: removed
        ];
        $this->list[$elem['service_id']]['exeptions'][$elem['date']] = $serviceException;
        $this->majServiceRangeDates($this->list[$elem['service_id']], $serviceException);
    }

    public function majServiceRangeDates(&$listElem, $elem) {
        if (empty($listElem['start_date'])) {
            $listElem['start_date'] = $elem['dateTimestamp'];
            $listElem['end_date'] = $elem['dateTimestamp'];
        }
        if ($listElem['start_date'] > $elem['dateTimestamp'])
            $listElem['start_date'] = $elem['dateTimestamp'];
        if ($listElem['end_date'] < $elem['dateTimestamp'])
            $listElem['end_date'] = $elem['dateTimestamp'];
    }
}