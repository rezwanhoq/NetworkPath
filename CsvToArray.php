<?php

class CsvToArray
{

    public $filename;

    public function __construct($filename)
    {
        $this->filename = $filename;
    }
    /**
     * creates multidimensional array from the CSV file
     */
    public function createArray()
    {
        $array = array_map('str_getcsv', file($this->filename));

        foreach ($array as $Key => $value) {
            $firstNode = $value[0];
            $secondNode = $value[1];
            $matrixArr[$firstNode][$secondNode] = $value[2];
            $matrixArr[$secondNode][$firstNode] = $value[2];
        }

        return $matrixArr;
    }
}
