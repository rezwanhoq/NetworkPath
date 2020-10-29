<?php

require_once "NetworkPath.php";
require_once "CsvToArray.php";

$file = 'CsvFile.csv';
if (file_exists($file)) {
    $csvFile = new CsvToArray($file);
    $matrixArr = $csvFile->createArray();
    $networkPath = new NetworkPath($matrixArr);
    $networkPath->run();
} else {
    exit('CSV file not found');
}
