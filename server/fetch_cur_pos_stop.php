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

// $errors = [];
// if (empty($_GET['cur_datetime']) || !preg_match('/\d{4}-\d{2}-\d{2} \d{2}:\d{2}(:\d{2})?/', $_GET['cur_datetime']))
//     $errors[] = 'mauvais cur_datetime. Utilisez le format Y-m-d H:i';
// if (empty($_GET['lng']))
//     $errors[] = 'Entrez la longitude recherchee';
// if (empty($_GET['lat']))
//     $errors[] = 'Entrez la latitude recherchee';
// if (!empty($errors)) {
//     echo json_encode(['errors'=>$errors]);
//     die();
// }

$sql = "SELECT pk id, stop_lon as lat, stop_lat as 'long'
        FROM gtfs_stop";
$stops = $DB->query($sql);
// echo "Stops: ".count($stops)."<br>\n";
echo json_encode($stops);