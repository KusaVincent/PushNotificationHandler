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

        $search_entry = $write ? $id : $data['BillRefNumber'];

        logThis(1,  "NOTIFICATION_DATA: " . json_encode($data));

        $check_result = handlesTransactionFile($search_entry, $save_transaction_file);

        if($check_result === true) return true;
        if($check_result === false && $write === false) return false;

        writeSAPFile($id, $msisdn, $amount, $created_at, $short_code);
        
        writeTransactionFile($id, $time, $save_transaction_file);

        return true;
}