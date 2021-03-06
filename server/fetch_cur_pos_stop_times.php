<?php

require __DIR__ . '/vendor/autoload.php';
session_start();
$conf = require __DIR__ . '/conf.php';

$confSQL = $conf['confSQL'];
$DB = new \HackQC17_8_Euclide\DB($confSQL['sql_host'], $confSQL['sql_user'], $confSQL['sql_pass'], $confSQL['sql_db']);

header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');
if (empty($_GET['api_key']) || $_GET['api_key'] != $conf['api_key']) {
    http_response_code(500);
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
if (isset($_GET['agency_pk']) && (empty($_GET['agency_pk']) || !is_numeric($_GET['agency_pk']) || !HackQC17_8_Euclide\GFTS\GtfsAgencyController::agencyExists($_GET['agency_pk']) ))
    $errors[] = 'agency_pk invalide';
if (!empty($errors)) {
    http_response_code(500);
    echo json_encode(['errors'=>$errors]);
    die();
}

$curDatetime = $_GET['cur_datetime'];
$curDate = substr($_GET['cur_datetime'], 0, 10);
$coords = [$_GET['lng'], $_GET['lat']];
$agency_pk = isset($_GET['agency_pk']) ? $_GET['agency_pk']*1 : null;

$service_ids = HackQC17_8_Euclide\GFTS\GtfsCalendarController::fetchDateServices($curDate, $agency_pk);
if (empty($service_ids)) {
    http_response_code(500);
    echo json_encode(['error'=>'Pas de service disponible pour la journée du: '.$curDate]);
    die();
}
$whereAgency = empty($agency_pk)?'':'st.agency_pk = '.($agency_pk*1).' AND ';
// $sql = "SELECT st.pk, st.route_pk, st.agency_pk, st.shape_pk, st.trip_headsign, st.direction_id, st.departure_time, st.arrival_time, st.service_id, a.agency_id
$sql = "SELECT st.pk
        FROM gtfs_trip st
            LEFT JOIN gtfs_agency a ON a.pk = st.agency_pk
        WHERE $whereAgency st.departure_time < DATE_ADD(:curDatetime, INTERVAL 30 MINUTE)
            AND st.arrival_time > DATE_SUB(:curDatetime, INTERVAL 10 MINUTE)
            AND st.service_id IN (".'"'.implode('", "', $service_ids).'"'.")";
$trips = $DB->query($sql, ['curDatetime' => $curDatetime]);
if (empty($trips)) {
    http_response_code(500);
    echo json_encode(['error'=>'Pas de trip pour cette date - heure: '.$curDatetime]);
    die();
}
// echo "Trips filtrés: ".count($trips)."<br>\n";
$trip_pk = [];
foreach ($trips as $v)
    $trip_pk[] = $v['pk'];
$whereAgency = empty($agency_pk)?'':'agency_pk = '.($agency_pk*1).' AND ';
$sql = "SELECT stop_pk stop_id, trip_pk trip_id, arrival_sec arr, departure_sec dep, stop_sequence
        FROM gtfs_stop_times st
         where $whereAgency st.trip_pk IN (".implode(', ', $trip_pk).") ";
$stopTimes = $DB->query($sql, ['curDatetime' => $curDatetime]);
foreach ($stopTimes as $key => $value)
    $stopTimes[$key] = [
        'stop_id' => 1*$value['stop_id'],
        'trip_id' => 1*$value['trip_id'],
        'arr' => 1*$value['arr'],
        'dep' => 1*$value['dep'],
        'stop_sequence' => 1*$value['stop_sequence']
    ];

if (empty($stopTimes)) {
    http_response_code(500);
    echo json_encode(['error'=>'Pas de stop times trouvés ... O.o le '.$curDatetime]);
    die();
}
// echo "Stop times filtrés: ".count($stopTimes)."<br>\n";
echo json_encode($stopTimes);