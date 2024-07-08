<?php
use Dotenv\Dotenv;

Dotenv::createImmutable(__DIR__)->load();

function prepareAPIRequest(array $data, string $save_transaction_file): bool
{
        if (!apiLogin($data["Username"], $data["Password"])) {
                logThis(2, "AUTH_FAILED: " . 'Credentials passed for auth are incorrect. Probably not from the expected source');
                return false;
        }

        $id = $data["TransID"];
        $time = $data["TransTime"];
        $name = trim($data["name"]);
        $created_at = $data["created_at"];
        $short_code = $data["BillRefNumber"];
        $amount = str_replace(',', '', number_format($data["TransAmount"], 1));

        if (!buildAndCompareHash($data, $amount))
                return false;

        logThis(1, "PASSED_DATA: " . json_encode($data));

        $check_result = handlesTransactionFile($id, $save_transaction_file);

        if ($check_result)
                return true;

        writeSAPFile($id, $name, $amount, $created_at, $short_code);

        writeTransactionFile($id, $time, $save_transaction_file);

        writeSMSFile([
                'bill_ref' => $short_code,
                'name' => $name,
                'mpesa_ref' => $id,
                'amount' => $amount,
                'created_at' => date('d-m-Y h:i A', strtotime($created_at)),
        ], getEnvVariables('sms_file'));

        return true;
}