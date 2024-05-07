<?php

function buildAndCompareHash(array $data, string $amount) : bool {
    $hash           = $data["Hash"];
    $secret_key     = getEnvVariables('secret_key');
    $credit_account = getEnvVariables('credit_account');

    // Hash_GeneratorForVal   = SecretKey + TransType + TransID + TransactionTime + TransAmount + BillRefNumber + MSISDN + Name
    // Hash_GeneratorForPush  = SecretKey + TransType + TransID + TransactionTime + TransAmount + CreditAccount + BillRefNumber + MSISDN + Name + "1"

    if(isset($data['Name'])) { //validation
        $hash_generator = $secret_key . $data["TransType"] . $data["TransID"] . $data["TransTime"] . $data["TransAmount"] . $data["BillRefNumber"] . $data["Mobile"] . $data['Name']; 
    } else {
        $hash_generator = $secret_key . $data["TransType"] . $data["TransID"] . $data["TransTime"] . $amount . $credit_account . $data["BillRefNumber"] . $data["Mobile"] . $data['name'] . 1; 
    }

    return (
            !compareHash($hash, $hash_generator) ? false : true
    ); 
}