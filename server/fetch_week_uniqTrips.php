<?php

require __DIR__ . '/vendor/autoload.php';
session_start();
$conf = require __DIR__ . '/conf.php';

$confSQL = $conf['confSQL'];
$DB = new \HackQC17_8_Euclide\DB($confSQL['sql_host'], $confSQL['sql_user'], $confSQL['sql_pass'], $confSQL['sql_db']);

header("Access-Control-Allow-Origin: *");
if (empty($_GET['api_key']) || $_GET['api_key'] != $conf['api_key']) {
    http_response_code(500);
    echo json_encode(['error'=>'mauvaise api_key']);
    die();
}

$errors = [];
if (empty($_GET['cur_datetime']) || !preg_match('/\d{4}-\d{2}-\d{2}( \d{2}(:\d{2}(:\d{2})?)?)?/', $_GET['cur_datetime']))
    $errors[] = 'mauvais cur_datetime. Utilisez le format Y-m-d H:i';
if (isset($_GET['agency_pk']) && (empty($_GET['agency_pk']) || !is_numeric($_GET['agency_pk']) || !HackQC17_8_Euclide\GFTS\GtfsAgencyController::agencyExists($_GET['agency_pk']) ))
    $errors[] = 'agency_pk invalide';
if (empty($_GET['stations']) || strpos($_GET['stations'], ',') === -1 && !is_numeric($_GET['stations']))
    $errors[] = 'Entrez une liste de pk de stations &stations=12,13,46';

if (!empty($errors)) {
    http_response_code(500);
    echo json_encode(['errors'=>$errors]);
    die();
}

$curDatetime = $_GET['cur_datetime'];
$curDate = substr($_GET['cur_datetime'], 0, 10);
$stations = array_map('intval', explode(',', $_GET['stations']));
$agency_pk = isset($_GET['agency_pk']) ? $_GET['agency_pk']*1 : null;

$service_ids = HackQC17_8_Euclide\GFTS\GtfsCalendarController::fetchDateServices($curDate, $agency_pk);
if (empty($service_ids)) {
    http_response_code(500);
    echo json_encode(['error'=>'Pas de service disponible pour la journée du: '.$curDate]);
    die();
}
$whereAgency = empty($agency_pk)?'':'t.agency_pk = '.($agency_pk*1).' AND ';
// $sql = "SELECT t.pk, t.route_pk, t.agency_pk, t.shape_pk, t.trip_headsign, t.direction_id, t.departure_time, t.arrival_time, t.service_id, a.agency_id
$sql = "SELECT t.pk, r.route_id, r.route_type, t.shape_pk, t.trip_headsign, t.direction_id, t.departure_time, t.arrival_time, t.service_id, a.agency_id
        FROM gtfs_trip t
            LEFT JOIN gtfs_agency a ON a.pk = t.agency_pk
            LEFT JOIN gtfs_route r ON r.pk = t.route_pk
        WHERE $whereAgency t.service_id IN (".'"'.implode('", "', $service_ids).'"'.")
        ORDER BY route_type";
$res = $DB->query($sql, ['curDatetime' => $curDatetime]);
if (empty($res)) {
    http_response_code(500);
    echo json_encode(['error'=>'Pas de trip pour cette date - heure: '.$curDatetime]);
    die();
}
$trips = [];
$trip_pk = [];
foreach ($res as $trip) {
    $trips[$trip['pk']] = $trip;
    $trip_pk[] = $trip['pk'];
}
// echo "Trips filtrés: ".count($trip_pk)."<br>\n";

$whereAgency = empty($agency_pk)?'':'agency_pk = '.($agency_pk*1).' AND ';
$sql = "SELECT trip_pk, COUNT(*) opportunites_zone, MIN(arrival_time) arrivee_zone, MAX(departure_time) depart_zone
        FROM gtfs_stop_times st
        WHERE $whereAgency
            stop_pk IN (".implode(', ', $stations).")
            AND st.trip_pk IN (".implode(', ', $trip_pk).")
        GROUP BY trip_pk";
$res = $DB->query($sql, ['curDatetime' => $curDatetime]);
if (empty($res)) {
    http_response_code(500);
    echo json_encode(['error'=>'Pas de stop times trouvés ... O.o le '.$curDatetime]);
    die();
}
// echo "trips filtrés: ".count($res)."<br>\n";
header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=file.csv");
header("Pragma: no-cache");
header("Expires: 0");

// $curDate
$countTrips = [
    'daily' => count($res),
    'h15-18' => 0,
    'h19-23' => 0,
    'uniq_routes' => []
];
$h15 = HackQC17_8_Euclide\GFTS\GtfsElemController::hourMinToSec('15:00:00');
$h19 = HackQC17_8_Euclide\GFTS\GtfsElemController::hourMinToSec('19:00:00');
$h24 = HackQC17_8_Euclide\GFTS\GtfsElemController::hourMinToSec('24:00:00');
$tripsThroughStops = [];
foreach ($res as $trip) {
    $arrivalSec = HackQC17_8_Euclide\GFTS\GtfsElemController::hourMinToSec($trip['arrivee_zone']);
    if ($arrivalSec >= $h15 && $arrivalSec < $h19)
        $countTrips['h15-18']++;
    else if ($arrivalSec >= $h19 && $arrivalSec < $h24)
        $countTrips['h19-23']++;
    $countTrips['uniq_routes'][$trip['route_id']] = 1;

    $curTrip = $trips[$trip['trip_pk']];
    $curTrip['opportunites_zone'] = $trip['opportunites_zone'];
    $curTrip['arrivee_zone'] = $trip['arrivee_zone'];
    $curTrip['depart_zone'] = $trip['depart_zone'];
    $tripsThroughStops[] = $curTrip;
}
echo "date;daily;h15-18;h19-23\n";
echo $curDate.';'.$countTrips['daily'].';'.$countTrips['h15-18'].';'.$countTrips['h19-23'].';'.count($countTrips['uniq_routes'])."\n\n";
echo "pk;route_id;route_type;shape_pk;trip_headsign;direction_id;departure_time;arrival_time;service_id;agency_id;opportunites_zone;arrivee_zone;depart_zone\n";
foreach ($tripsThroughStops as $trip) {
    echo implode(';', $trip)."\n";
}
