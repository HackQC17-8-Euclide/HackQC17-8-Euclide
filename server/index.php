<?php

require __DIR__ . '/vendor/autoload.php';
session_start();
$conf = require __DIR__ . '/conf.php';

$confSQL = $conf['confSQL'];
$DB = new \HackQC17_8_Euclide\DB($confSQL['sql_host'], $confSQL['sql_user'], $confSQL['sql_pass'], $confSQL['sql_db']);

$res = $DB->query('SELECT pk, agency_id, agency_name FROM gtfs_agency');
$agencies = [];
foreach ($res as $val) {
    $agencies[$val['pk']] = [
        'pk' => $val['pk'],
        'agency_id' => $val['agency_id'],
        'agency_name' => $val['agency_name']
    ];
}
$res = $DB->query('SELECT agency_pk, count(*) count FROM gtfs_stop GROUP BY agency_pk');
foreach ($res as $val)
    $agencies[$val['agency_pk']]['gtfs_stop'] = $val['count'];
$res = $DB->query('SELECT agency_pk, count(*) count FROM gtfs_route GROUP BY agency_pk');
foreach ($res as $val)
    $agencies[$val['agency_pk']]['gtfs_route'] = $val['count'];
$res = $DB->query('SELECT agency_pk, count(*) count FROM gtfs_trip GROUP BY agency_pk');
foreach ($res as $val)
    $agencies[$val['agency_pk']]['gtfs_trip'] = $val['count'];
$res = $DB->query('SELECT agency_pk, count(*) count FROM gtfs_stop_times GROUP BY agency_pk');
foreach ($res as $val)
    $agencies[$val['agency_pk']]['gtfs_stop_times'] = $val['count'];

?>

<h1>Contenu de notre base de données</h1>

<table>
    <thead>
        <tr>
            <th>Agency id</th>
            <th>Agency name</th>
            <th>Stops</th>
            <th>Routes</th>
            <th>Trips</th>
            <th>Stop-times</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($agencies as $key => $agency): ?>
            <tr>
                <td><?= $agency['agency_id'] ?></td>
                <td><?= $agency['agency_name'] ?></td>
                <td style="text-align: right"><?= number_format($agency['gtfs_stop'], 0, ',', ' '); ?></td>
                <td style="text-align: right"><?= number_format($agency['gtfs_route'], 0, ',', ' '); ?></td>
                <td style="text-align: right"><?= number_format($agency['gtfs_trip'], 0, ',', ' '); ?></td>
                <td style="text-align: right"><?= number_format($agency['gtfs_stop_times'], 0, ',', ' '); ?></td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>