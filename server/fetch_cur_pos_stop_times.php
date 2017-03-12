<?php

require __DIR__ . '/vendor/autoload.php';
session_start();
$conf = require __DIR__ . '/conf.php';

$confSQL = $conf['confSQL'];
$DB = new \HackQC17_8_Euclide\DB($confSQL['sql_host'], $confSQL['sql_user'], $confSQL['sql_pass'], $confSQL['sql_db']);

header('Content-Type: application/json');
if (empty($_GET['api_key']) || $_GET['api_key'] != $conf['api_key']) {
    echo json_encode(['error'=>'mauvaise api_key']);
    die();
}

$errors = [];
if (empty($_GET['cur_datetime']) || !preg_match('/\d{4}-\d{2}-\d{2} \d{2}:\d{2}(:\d{2})?/', $_GET['cur_datetime']))
    $errors[] = 'mauvais cur_datetime. Utilisez le format Y-m-d H:i';
if (empty($_GET['lng']))
    $errors[] = 'Entrez la longitude recherchee';
if (empty($_GET['lat']))
    $errors[] = 'Entrez la latitude recherchee';
if (!empty($errors)) {
    echo json_encode(['errors'=>$errors]);
    die();
}

$curDatetime = $_GET['cur_datetime'];
$curDate = substr($_GET['cur_datetime'], 0, 10);
$coords = [$_GET['lng'], $_GET['lat']];

// $sql = "SELECT *
$sql = "SELECT DISTINCT c.service_id
        FROM gtfs_calendar_dates cd
            LEFT JOIN gtfs_calendar c ON c.service_id = cd.service_id
        WHERE (
               cd.date = :curDatetime AND cd.exception_type = 1
                OR
               cd.date = :curDatetime AND cd.exception_type != 2 AND (
                            WEEKDAY(:curDatetime)=0 AND c.monday = 1
                   OR   WEEKDAY(:curDatetime)=1 AND c.tuesday = 1
                   OR   WEEKDAY(:curDatetime)=2 AND c.wednesday = 1
                   OR   WEEKDAY(:curDatetime)=3 AND c.thursday = 1
                   OR   WEEKDAY(:curDatetime)=4 AND c.friday = 1
                   OR   WEEKDAY(:curDatetime)=5 AND c.saturday = 1
                   OR   WEEKDAY(:curDatetime)=6 AND c.sunday = 1
               )
        )";
$services = $DB->query($sql, ['curDatetime' => $curDate]);
// echo "Services filtrés: ".count($services)."<br>\n";
$service_ids = [];
foreach ($services as $v)
    $service_ids[] = $v['service_id'];


// $sql = "SELECT st.pk, st.route_pk, st.agency_pk, st.shape_pk, st.trip_headsign, st.direction_id, st.departure_time, st.arrival_time, st.service_id, a.agency_id
$sql = "SELECT st.pk
        FROM gtfs_trip st
            LEFT JOIN gtfs_agency a ON a.pk = st.agency_pk
        WHERE st.departure_time < DATE_ADD(:curDatetime, INTERVAL 30 MINUTE)
            AND st.arrival_time > :curDatetime
            AND st.service_id IN (".'"'.implode('", "', $service_ids).'"'.")";
$trips = $DB->query($sql, ['curDatetime' => $curDatetime]);
// echo "Trips filtrés: ".count($trips)."<br>\n";
$trip_pk = [];
foreach ($trips as $v)
    $trip_pk[] = $v['pk'];
$sql = "SELECT stop_pk stop_id, trip_pk trip_id, arrival_time arr, departure_time dep, stop_sequence
        FROM gtfs_stop_times st
        WHERE st.trip_pk IN (".implode(', ', $trip_pk).")";
$stopTimes = $DB->query($sql, ['curDatetime' => $curDatetime]);
// echo "Stop times filtrés: ".count($stopTimes)."<br>\n";
echo json_encode($stopTimes);