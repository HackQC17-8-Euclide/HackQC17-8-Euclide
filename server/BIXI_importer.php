<?php

require __DIR__ . '/vendor/autoload.php';
session_start();
$conf = require __DIR__ . '/conf.php';

$confSQL = $conf['confSQL'];
$DB = new \HackQC17_8_Euclide\DB($confSQL['sql_host'], $confSQL['sql_user'], $confSQL['sql_pass'], $confSQL['sql_db']);


$BIXI_path = "input/BIXI/";
if (!file_exists($BIXI_path)) { mkdir($BIXI_path);
	throw new \Exception("Hey, le dossier n'existait pas, il faudrait que tu ajoutes tes donnees dans input/BIXI/", 1); }

echo "<h2>Import OD_simple_2016</h2><br>\n";
$OD_simple_2016 = new \HackQC17_8_Euclide\GFTS\BixiController($BIXI_path.'OD_simple_2016/');