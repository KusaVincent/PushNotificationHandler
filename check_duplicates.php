<?php

function checkDuplicates(string $mpesaRef, array $csvData) : bool
{
    foreach ($csvData as $row) {
        if($row === $mpesaRef) return true;
    }

    return false;
}