<?php

function compareHash($transmitted_hash, $prepared_hash_string) : bool
{
    $hash = base64_encode(hash('sha256', $prepared_hash_string));

    return (
        $hash !== $transmitted_hash
                ? false
                : true
    );
}