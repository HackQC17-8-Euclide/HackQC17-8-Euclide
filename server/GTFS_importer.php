<?php

require __DIR__ . '/vendor/autoload.php';
session_start();
$conf = require __DIR__ . '/conf.php';

$confSQL = $conf['confSQL'];
$DB = new \HackQC17_8_Euclide\DB($confSQL['sql_host'], $confSQL['sql_user'], $confSQL['sql_pass'], $confSQL['sql_db']);


$GTFS_path = "input/GTFS/";
if (!file_exists($GTFS_path)) { mkdir($GTFS_path);
	throw new \Exception("Hey, le dossier n'existait pas, il faudrait que tu ajoutes tes donnees dans input/GTFS/", 1); }

// echo "<h2>Import RTCS_Shawinigan</h2><br>\n";
// $RTCS_Shawinigan = new \HackQC17_8_Euclide\GFTS\GtfsController($GTFS_path.'RTCS_Shawinigan/');
echo "<h2>Import RTL_Longueuil</h2><br>\n";
$RTL_Longueuil = new \HackQC17_8_Euclide\GFTS\GtfsController($GTFS_path.'RTL_Longueuil/');
// echo "<h2>Import STS_Sherbrooke</h2><br>\n";
// $STS_Sherbrooke = new \HackQC17_8_Euclide\GFTS\GtfsController($GTFS_path.'STS_Sherbrooke/');
// echo "<h2>Import STO_Gatineau</h2><br>\n";
// $STO_Gatineau = new \HackQC17_8_Euclide\GFTS\GtfsController($GTFS_path.'STO_Gatineau/');
// echo "<h2>Import AMT_TRAINS</h2><br>\n";
// $AMT_TRAINS = new \HackQC17_8_Euclide\GFTS\GtfsController($GTFS_path.'AMT_Montreal/AMT_TRAINS/');
// echo "<h2>Import STL_Laval</h2><br>\n";
// $STL_Laval = new \HackQC17_8_Euclide\GFTS\GtfsController($GTFS_path.'STL_Laval/');
// echo "<h2>Import RTC_Quebec</h2><br>\n";
// $RTC_Quebec = new \HackQC17_8_Euclide\GFTS\GtfsController($GTFS_path.'RTC_Quebec/');
// echo "<h2>Import STM_Montreal</h2><br>\n";
// $STM_Montreal = new \HackQC17_8_Euclide\GFTS\GtfsController($GTFS_path.'STM_Montreal/');
