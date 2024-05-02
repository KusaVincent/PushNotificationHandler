<?php

class Authenticator
{
    public static function apiLogin(string $user_name, string $password): bool
    {
        if(!isset($_ENV['USER_NAME']) || !isset($_ENV['PASSWORD'])) throw new Exception("Required environment variables are not set.");

        return $_ENV['USER_NAME'] === $user_name && password_verify($password, $_ENV['PASSWORD']);
    }

    public static function validateHash(array $data) : mixed
    {
        if (!isset($_ENV['SECRET_KEY']) || !isset($_ENV['CREDIT_ACCOUNT'])) throw new Exception("Required environment variables are not set.");

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

        $hash_generator = $secret_key . $type . $id . $time . $amount . $credit_account . $bill_ref . $msisdn . $name . 1; 

        if(!self::compareHash($hash, $hash_generator)) throw new Exception("Hash comparison failed.");
    }

    private static function compareHash(string $transmitted_hash, string $prepared_hash_string) : bool
    {
        $hash = base64_encode(hash('sha256', $prepared_hash_string));
        return $hash !== $transmitted_hash;
    }

    private static function validateDuplicates(string $mpesaRef, array $csvData) : bool
    {
        foreach ($csvData as $row) {
            if($row === $mpesaRef) return true;
        }

        return false;
    }
}