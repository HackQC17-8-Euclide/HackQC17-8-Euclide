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
        global $DB;
        $this->path = $path;
        $this->log_path = $path;

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
        if (!empty($this->AgencyCtrl->curAgencyPk)) {
            $DB->exec("DELETE FROM gtfs_stop_times WHERE agency_pk = ".$this->AgencyCtrl->curAgencyPk);
            $DB->exec("DELETE FROM gtfs_trip WHERE agency_pk = ".$this->AgencyCtrl->curAgencyPk);
            $DB->exec("DELETE FROM gtfs_shape WHERE agency_pk = ".$this->AgencyCtrl->curAgencyPk);
            $DB->exec("DELETE FROM gtfs_calendar_dates WHERE agency_pk = ".$this->AgencyCtrl->curAgencyPk);
            $DB->exec("DELETE FROM gtfs_calendar WHERE agency_pk = ".$this->AgencyCtrl->curAgencyPk);
            $DB->exec("DELETE FROM gtfs_route WHERE agency_pk = ".$this->AgencyCtrl->curAgencyPk);
            $DB->exec("DELETE FROM gtfs_stop WHERE agency_pk = ".$this->AgencyCtrl->curAgencyPk);
            $DB->exec("DELETE FROM gtfs_agency WHERE pk = ".$this->AgencyCtrl->curAgencyPk);
        }
        $this->StopCtrl = new \HackQC17_8_Euclide\GFTS\GtfsStopController($this->path.'stops.txt', $this);
            $this->AgencyCtrl->list[$this->AgencyCtrl->curAgencyId]['spatialExtent'] = $this->StopCtrl->spatialExtent;
            $this->AgencyCtrl->export();
            $this->StopCtrl->export();
        $this->RouteCtrl = new \HackQC17_8_Euclide\GFTS\GtfsRouteController($this->path.'routes.txt', $this);
        $this->RouteCtrl->export();
        $this->CalendarCtrl = new \HackQC17_8_Euclide\GFTS\GtfsCalendarController($this->path.'calendar.txt', $this);
        $this->CalendarDateCtrl = new \HackQC17_8_Euclide\GFTS\GtfsCalendarDateController($this->path.'calendar_dates.txt', $this);
        $this->CalendarCtrl->export();
        $this->CalendarDateCtrl->export();
        $this->ShapeCtrl = new \HackQC17_8_Euclide\GFTS\GtfsShapeController($this->path.'shapes.txt', $this);
        $this->TripCtrl = new \HackQC17_8_Euclide\GFTS\GtfsTripController($this->path.'trips.txt', $this);
        $this->ShapeCtrl->export();
        $this->TripCtrl->export();
        $this->TripCtrl->fetchDataDB();
        $this->StopTimeCtrl = new \HackQC17_8_Euclide\GFTS\GtfsStopTimeController($this->path.'stop_times.txt', $this, ['autoExport'=>true]);
        $this->TripCtrl->update(['departure_time', 'arrival_time', 'departure_sec', 'arrival_sec']);
        $this->timeLoad = [
            'AgencyCtrl' => $this->AgencyCtrl->timeLoad,
            'StopCtrl' => $this->StopCtrl->timeLoad,
            'RouteCtrl' => $this->RouteCtrl->timeLoad,
            'CalendarCtrl' => $this->CalendarCtrl->timeLoad,
            'CalendarDateCtrl' => $this->CalendarDateCtrl->timeLoad,
            'ShapeCtrl' => $this->ShapeCtrl->timeLoad,
            'TripCtrl' => $this->TripCtrl->timeLoad,
            'StopTimeCtrl' => $this->StopTimeCtrl->timeLoad
        ];
        $this->counts = [
            'AgencyCtrl' => $this->AgencyCtrl->countRows,
            'StopCtrl' => $this->StopCtrl->countRows,
            'RouteCtrl' => $this->RouteCtrl->countRows,
            'CalendarCtrl' => $this->CalendarCtrl->countRows,
            'CalendarDateCtrl' => $this->CalendarDateCtrl->countRows,
            'ShapeCtrl' => $this->ShapeCtrl->countRows,
            'TripCtrl' => $this->TripCtrl->countRows,
            'StopTimeCtrl' => $this->StopTimeCtrl->countRows
        ];
        var_dump($this->timeLoad);
        var_dump($this->counts);
    }
}