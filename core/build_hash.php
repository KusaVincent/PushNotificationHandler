<?php

function buildAndCompareHash(array $data, string $amount) : bool {
    $hash           = $data["Hash"];
    $secret_key     = getEnvVariables('secret_key');
    $credit_account = getEnvVariables('credit_account');
    $name           = isset($data['name']) ? $data['name'] : $data['Name'];

    // Hash_Generator  = SecretKey + TransType + TransID + TransactionTime + TransAmount + CreditAccount + BillRefNumber + MSISDN + Name + "1"

    $hash_generator = $secret_key . $data["TransType"] . $data["TransID"] . $data["TransTime"] . $amount
                            . $credit_account . $data["BillRefNumber"] . $data["Mobile"] . $name  . 1; 

    return (
            !compareHash($hash, $hash_generator) ? false : true
    ); 
}