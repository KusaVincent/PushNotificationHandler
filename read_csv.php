<?php

function readCSV(string $csvFilePath) : array
{
    $csvData = array();

    if (file_exists($csvFilePath)) {
        if (($handle = fopen($csvFilePath, "r")) !== false) {
            fgetcsv($handle);
    
            $data = array();
    
            while (($row = fgetcsv($handle, 1000, ",")) !== false) {
                $data[] = $row;
            }
    
            fclose($handle);
    
            foreach ($data as $row) {
                $csvData[] = $row[0];
            }

            return $csvData;
        } else {
            echo "Error opening the file.";
        }
    } else {
        echo "The file does not exist.";
    }
}