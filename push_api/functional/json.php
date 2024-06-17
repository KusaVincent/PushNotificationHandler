<?php

function json_response(int $code = 200, array $message) : string
{
    header_remove();
    http_response_code($code);
    header("Cache-Control: no-transform,public,max-age=300,s-maxage=900");
    header('Content-Type: application/json');
    
    header('Status: '. $code);

    return json_encode($message);
}