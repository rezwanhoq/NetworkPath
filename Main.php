<?php
$array = array_map('str_getcsv', file('csvData.csv'));
$header = array_shift($array);
array_walk($array, '_combine_array', $header);

function _combine_array(&$row, $key, $header)
{
    $row = array_combine($header, $row);
}

foreach ($array as $arrayKey => $value) {
    $firstNode = $value['DeviceFrom'];
    $secondNode = $value['DeviceTo'];
    $matrixArr[$firstNode][$secondNode] = $value['Latency'];
    $matrixArr[$secondNode][$firstNode] = $value['Latency'];
}

var_dump($matrixArr);

