<?php

function json_response(int $code = 200, array $message) : string
{
    header_remove();
    http_response_code($code);
    header("Cache-Control: no-transform,public,max-age=300,s-maxage=900");
    header('Content-Type: application/json');

    $status = [
        200 => 'OK',
        400 => 'Bad Request',
        403 => 'Forbidden',
        401 => 'Unauthorized'
    ];

    header('Status: '. $status[$code]);

    return json_encode($message);
}