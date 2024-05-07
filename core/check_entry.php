<?php

function validateORCheckDuplicates(string $search_entry, array $csvData) : bool {
    if(sizeof($csvData) > getEnvVariables('file_size')) return binarySearch($search_entry, $csvData);
    
    return linearSearch($search_entry, $csvData);
}

function linearSearch(string $search_entry, array $csvData): bool {
    foreach ($csvData as $row) {
        if ($row[0] === $search_entry) {
            logThis(1, $search_entry . ' : is found');
            return true;
        }
    }

    return false;
}

function binarySearch(string $search_entry, array $csvData): bool {
    $left = 0;
    $right = sizeof($csvData) - 1;

    while ($left <= $right) {
        $mid = floor(($left + $right) / 2);

        if ($csvData[$mid][0] === $search_entry) {
            logThis(1, $search_entry . ' : is found');
            return true;
        }

        if ($csvData[$mid][0] < $search_entry) {
            $left = $mid + 1;
        } else {
            $right = $mid - 1;
        }
    }

    return false;
}