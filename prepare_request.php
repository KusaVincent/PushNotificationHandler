<?php
use Dotenv\Dotenv;

Dotenv::createImmutable(__DIR__)->load();

function prepareAPIRequest($data)
{
        if (!apiLogin($data["Username"], $data["Password"])) 
                return;

        $secret_key     = $_ENV['SECRET_KEY'];
        $credit_account = $_ENV['CREDIT_ACCOUNT'];

        $hash       = $data["Hash"];
        $name       = $data["name"];
        $msisdn     = $data["Mobile"];
        $id         = $data["TransID"];
        $type       = $data["TransType"];
        $time       = $data["TransTime"];
        $created_at = $data["created_at"];
        $bill_ref   = $data["BillRefNumber"];
        $short_code = $data["BusinessShortCode"];
        $amount     = str_replace(',', '', number_format($data["TransAmount"], 1));

        // Hash_generator  = SecretKey + TransType + TransID + TransactionTime + TransAmount + CreditAccount + BillRefNumber + MSISDN + Name + "1"

        $hash_generator = $secret_key . $type . $id . $time . $amount . $credit_account . $bill_ref . $msisdn . $name . 1; 

        if(!compareHash($hash, $hash_generator)) 
                return;

        prepareInsert($data);
}