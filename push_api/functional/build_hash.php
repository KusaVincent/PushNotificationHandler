<?php

function buildAndCompareHash(array $data, string $amount) : bool {
    $hash           = $data["Hash"];
    $credit_account = getEnvVariables('credit_account');

    // Hash_GeneratorForValidation       = SecretKey + TransType + TransID + TransactionTime + TransAmount + BillRefNumber + MSISDN + Name
    // Hash_GeneratorForPushNotification = SecretKey + TransType + TransID + TransactionTime + TransAmount + CreditAccount + BillRefNumber + MSISDN + Name + "1"

    $secret_key     = getEnvVariables('confirmation_secret_key');
    $hash_generator = $secret_key . $data["TransType"] . $data["TransID"] . $data["TransTime"] . $amount . $credit_account . $data["BillRefNumber"] . $data["Mobile"] . $data['name'] . 1; 

    return compareHash($hash, $hash_generator);
}