<?php

require_once "NetworkPath.php";

$filename = 'csvData.csv';
$array = array_map('str_getcsv', file($filename));


foreach ($array as $arrayKey => $value) {
    $firstNode = $value[0];
    $secondNode = $value[1];
    $matrixArr[$firstNode][$secondNode] = $value[2];
    $matrixArr[$secondNode][$firstNode] = $value[2];
}

$networkPath = new NetworkPath($matrixArr);
$networkPath->run();
