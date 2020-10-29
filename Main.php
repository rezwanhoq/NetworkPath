<?php

require_once "NetworkPath.php";
require_once "CsvToArray.php";

$file = 'csvData.csv';
$csvFile = new CsvToArray($file);
$matrixArr = $csvFile->createArray();
$networkPath = new NetworkPath($matrixArr);
$networkPath->run();
