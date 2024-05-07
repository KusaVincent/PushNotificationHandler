<?php

function validateORCheckDuplicates(string $mpesaRef, array $csvData) : bool {
    if(sizeof($csvData) > getEnvVariables('file_size')) return binarySearch($mpesaRef, $csvData);
    
    return linearSearch($mpesaRef, $csvData);
}

function linearSearch(string $mpesaRef, array $csvData): bool {
    foreach ($csvData as $row) {
        if ($row[0] === $mpesaRef) {
            logThis(1, $mpesaRef . ' : is duplicated');
            return true;
        }
    }

    return false;
}

function binarySearch(string $mpesaRef, array $csvData): bool {
    $left = 0;
    $right = sizeof($csvData) - 1;

    while ($left <= $right) {
        $mid = floor(($left + $right) / 2);

        if ($csvData[$mid][0] === $mpesaRef) {
            logThis(1, $mpesaRef . ' : is duplicated');
            return true;
        }

        if ($csvData[$mid][0] < $mpesaRef) {
            $left = $mid + 1;
        } else {
            $right = $mid - 1;
        }
    }

    return false;
}