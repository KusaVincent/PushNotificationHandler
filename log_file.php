<?php

function logThis(int $LEVEL, string $logThis) : void
{
    $logLevel = "";
    $logFile = 'sap\logs\notifications.log';

    switch ($LEVEL) {
        case 1:
            $logLevel = "INFO";
            break;
        case 2:
            $logLevel = "ERROR";
            break;
        case 3:
            $logLevel = "DEBUG";
            break;
        default :
            $logLevel = "NOTICE";
    }

    $e = new Exception();
    $trace = $e->getTrace();

    //position 0 would be the line that called this function so we ignore it
    $last_call = isset($trace[1]) ? $trace[1] : array();
    $lineArr = $trace[0];

    $function = isset($last_call['function']) ? $last_call['function'] . "()|" : "";
    $line = isset($lineArr['line']) ? $lineArr['line'] . "|" : "";
    $file = isset($lineArr['file']) ? $lineArr['file'] . "|" : "";

    $remote_ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] . "|" : "";

    $date = date("Y-m-d H:i:s");
    $string = $date . "|$logLevel|$file$function$remote_ip$line" . $logThis . "\n";

    file_put_contents($logFile, $string, FILE_APPEND);
}