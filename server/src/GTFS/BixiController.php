<?php

namespace HackQC17_8_Euclide\GFTS;

class BixiController {
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

        $DB->exec("DELETE FROM bixi_od");

        echo "<h2>Import BIXI OD_2016-04</h2><br>\n";
        $this->BixiODCtrl = new \HackQC17_8_Euclide\GFTS\BixiODController($this->path.'OD_2016-04.csv', $this, ['separator'=>';']);
        $this->BixiODCtrl->export();
        unset($this->BixiODCtrl);
        echo "<h2>Import BIXI OD_2016-05</h2><br>\n";
        $this->BixiODCtrl = new \HackQC17_8_Euclide\GFTS\BixiODController($this->path.'OD_2016-05.csv', $this, ['separator'=>';']);
        $this->BixiODCtrl->export();
        unset($this->BixiODCtrl);
        echo "<h2>Import BIXI OD_2016-06</h2><br>\n";
        $this->BixiODCtrl = new \HackQC17_8_Euclide\GFTS\BixiODController($this->path.'OD_2016-06.csv', $this, ['separator'=>';']);
        $this->BixiODCtrl->export();
        unset($this->BixiODCtrl);
        echo "<h2>Import BIXI OD_2016-07</h2><br>\n";
        $this->BixiODCtrl = new \HackQC17_8_Euclide\GFTS\BixiODController($this->path.'OD_2016-07.csv', $this, ['separator'=>';']);
        $this->BixiODCtrl->export();
        unset($this->BixiODCtrl);
        echo "<h2>Import BIXI OD_2016-08</h2><br>\n";
        $this->BixiODCtrl = new \HackQC17_8_Euclide\GFTS\BixiODController($this->path.'OD_2016-08.csv', $this, ['separator'=>';']);
        $this->BixiODCtrl->export();
        unset($this->BixiODCtrl);
        echo "<h2>Import BIXI OD_2016-09</h2><br>\n";
        $this->BixiODCtrl = new \HackQC17_8_Euclide\GFTS\BixiODController($this->path.'OD_2016-09.csv', $this, ['separator'=>';']);
        $this->BixiODCtrl->export();
        unset($this->BixiODCtrl);
        echo "<h2>Import BIXI OD_2016-10</h2><br>\n";
        $this->BixiODCtrl = new \HackQC17_8_Euclide\GFTS\BixiODController($this->path.'OD_2016-10.csv', $this, ['separator'=>';']);
        $this->BixiODCtrl->export();
        unset($this->BixiODCtrl);
        echo "<h2>Import BIXI OD_2016-11</h2><br>\n";
        $this->BixiODCtrl = new \HackQC17_8_Euclide\GFTS\BixiODController($this->path.'OD_2016-11.csv', $this, ['separator'=>';']);
        $this->BixiODCtrl->export();
        unset($this->BixiODCtrl);
    }
}