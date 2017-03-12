<?php

require __DIR__ . '/vendor/autoload.php';
session_start();
$conf = require __DIR__ . '/conf.php';

header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');
if (empty($_GET['api_key']) || $_GET['api_key'] != $conf['api_key']) {
    echo json_encode(['error'=>'mauvaise api_key']);
    die();
}

if (isset($_GET['lat_min'], $_GET['lat_max'], $_GET['lng_min'], $_GET['lng_max'], $_GET['start_hour'], $_GET['duree'])) {
    $_SESSION['upload'] = [
        'lat_min' => $_GET['lat_min']*1,
        'lat_max' => $_GET['lat_max']*1,
        'lng_min' => $_GET['lng_min']*1,
        'lng_max' => $_GET['lng_max']*1,
        'start_hour' => $_GET['start_hour'],
        'duree' => $_GET['duree']*1
    ];
    $_SESSION['upload']['file_name'] = 'res_algo_'.$_SESSION['upload']['start_hour'].'_'.$_SESSION['upload']['duree'].'_'.$_SESSION['upload']['lat_min'].'_'.$_SESSION['upload']['lat_max'].'_'.$_SESSION['upload']['lng_min'].'_'.$_SESSION['upload']['lng_max'].'.csv';
    file_put_contents($_SESSION['upload']['file_name'], "Génération matrice d'accessibilité le ".date("Y-m-d H:i:s")."\n"."\n", FILE_APPEND);
    file_put_contents($_SESSION['upload']['file_name'], implode(';', array_keys($_SESSION['upload']))."\n", FILE_APPEND);
    file_put_contents($_SESSION['upload']['file_name'], implode(';', $_SESSION['upload'])."\n"."\n", FILE_APPEND);
    file_put_contents($_SESSION['upload']['file_name'], 'lng;lat;score'."\n", FILE_APPEND);
    echo json_encode(["création fichier"=>$_SESSION['upload']['file_name']]);
} else if (isset($_GET['lng'], $_GET['lat'], $_GET['score'])) {
    $point = [
        'lng' => $_GET['lng']*1,
        'lat' => $_GET['lat']*1,
        'score' => $_GET['score']*1
    ];
    file_put_contents($_SESSION['upload']['file_name'], implode(';', $point)."\n", FILE_APPEND);
    echo json_encode("point reçu");
} else {
    echo json_encode([
        'error'=>"mauvais arguments !",
        'initialisation export (variables)'=>['api_key', 'lat_min', 'lat_max', 'lng_min', 'lng_max', 'start_hour', 'duree'],
        'ajout score (variables)'=>['api_key', 'lng', 'lat', 'score']
    ]);
}
