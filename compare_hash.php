<?php

function compareHash(string $transmitted_hash, string $prepared_hash_string) : bool
{
    $hash = base64_encode(hash('sha256', $prepared_hash_string));

    if($hash !== $transmitted_hash) {
        logThis(2,  "HASH_MISMATCH: " . "Hash comparison has failed. System will go ahead to fail the request as it is not trusted.\n $hash \n $transmitted_hash ");
        return false;
    }

    logThis(4, $hash . "matches the API " . $transmitted_hash . "\n" . "API request will continue processing");
    return true;
}