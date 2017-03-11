<?php

namespace HackQC17_8_Euclide\GFTS;

class GtfsController {
    public $path;
    public $AgencyCtrl;
    public $StopCtrl;
    public $RouteCtrl;
    public $CalendarCtrl;
    public $CalendarDateCtrl;
    public $ShapeCtrl;
    public $TripCtrl;
    public $StopTimeCtrl;
    public $timeLoad;
    function __construct($path) {
        $this->path = $path;

            // gtfs_zone
        // gtfs_agency
        // gtfs_stop
            // gtfs_route_type
        // gtfs_route
            // gtfs_direction
        // gtfs_calendar
        // gtfs_calendar_dates
        // gtfs_shape
        // gtfs_trip
        // gtfs_stop_times
        // gtfs_frequency
        // gtfs_shape_stops

        $this->AgencyCtrl = new \HackQC17_8_Euclide\GFTS\GtfsAgencyController($this->path.'agency.txt', $this);
        $this->StopCtrl = new \HackQC17_8_Euclide\GFTS\GtfsStopController($this->path.'stops.txt', $this);
            $this->AgencyCtrl->list[0]['spatialExtent'] = $this->StopCtrl->spatialExtent;
            // $this->AgencyCtrl->export();
            // $this->StopCtrl->export();
        $this->RouteCtrl = new \HackQC17_8_Euclide\GFTS\GtfsRouteController($this->path.'routes.txt', $this);
        $this->CalendarCtrl = new \HackQC17_8_Euclide\GFTS\GtfsCalendarController($this->path.'calendar.txt', $this);
        $this->CalendarDateCtrl = new \HackQC17_8_Euclide\GFTS\GtfsCalendarDateController($this->path.'calendar_dates.txt', $this);
        $this->ShapeCtrl = new \HackQC17_8_Euclide\GFTS\GtfsShapeController($this->path.'shapes.txt', $this);
        $this->TripCtrl = new \HackQC17_8_Euclide\GFTS\GtfsTripController($this->path.'trips.txt', $this);
        // $this->StopTimeCtrl = new \HackQC17_8_Euclide\GFTS\GtfsStopTimeController($this->path.'stop_times.txt', $this);
        $this->timeLoad = [
            'AgencyCtrl' => $this->AgencyCtrl->timeLoad,
            'StopCtrl' => $this->StopCtrl->timeLoad,
            'RouteCtrl' => $this->RouteCtrl->timeLoad,
            'CalendarCtrl' => $this->CalendarCtrl->timeLoad,
            'CalendarDateCtrl' => $this->CalendarDateCtrl->timeLoad,
            'ShapeCtrl' => $this->ShapeCtrl->timeLoad,
            'TripCtrl' => $this->TripCtrl->timeLoad,
            // 'StopTimeCtrl' => $this->StopTimeCtrl->timeLoad
        ];
        var_dump($this->timeLoad);
    }
}