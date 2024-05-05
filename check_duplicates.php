<?php

function checkDuplicates(string $mpesaRef, array $csvData) : bool {
    foreach ($csvData as $row) {
        if($row[0] === $mpesaRef){
            logThis(1, $mpesaRef . ' : is duplicated');
            return true;
        } 
    }

    return false;
}