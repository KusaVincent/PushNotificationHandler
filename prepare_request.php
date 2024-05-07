<?php
use Dotenv\Dotenv;

Dotenv::createImmutable(__DIR__)->load();

function prepareAPIRequest(array $data, string $save_transaction_file, bool $write) : bool {
        if (!apiLogin($data["Username"], $data["Password"])) {
                logThis(2,  "AUTH_FAILED: " . 'Credentials passed for auth are incorrect. Probably not from the expected source');
                return false;
        }

        $msisdn     = $data["Mobile"];
        $id         = $data["TransID"];
        $time       = $data["TransTime"];
        $created_at = $data["created_at"];
        $short_code = $data["BusinessShortCode"];
        $amount     = str_replace(',', '', number_format($data["TransAmount"], 1));

        if(!buildAndCompareHash($data, $amount)) return false;

        logThis(1,  "NOTIFICATION_DATA: " . json_encode($data));

        $check_result = handlesTransactionFile($id, $save_transaction_file);

        if(($check_result === true || ($check_result === false && $write === false))) return true;

        writeSAPFile($id, $msisdn, $amount, $created_at, $short_code);
        
        writeTransactionFile($id, $time, $save_transaction_file);

        return true;
}