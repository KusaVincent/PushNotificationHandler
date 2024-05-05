<?php
use Dotenv\Dotenv;

Dotenv::createImmutable(__DIR__)->load();

function prepareAPIRequest(array $data) : bool
{
        $save_transaction_file = getEnvVariables('transaction_file');
        
        if (!apiLogin($data["Username"], $data["Password"])) {
                logThis(2,  "AUTH_FAILED: " . 'Credentials passed for auth are incorrect. Probably not from the expected source');
                return false;
        }

        $secret_key     = getEnvVariables('secret_key');
        $credit_account = getEnvVariables('credit_account');

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

        if(!compareHash($hash, $hash_generator)) return false;

        logThis(1,  "NOTIFICATION_DATA: " . json_encode($data));

        try {
                if(checkDuplicates($id, HandleCSV::readCSV($save_transaction_file))) {
                        logThis(1,  "DUPLICATE_DATA: " . 'Passed enrty duplicated');
                        return true;
                }
        } catch(Exception $e) {
                logThis(3, "An error occurred: " . $e->getMessage() . "\n" . $e);
        }

        try {
                $formattedDate = date('Ymd', strtotime($created_at));

                $sap_data = array(
                        'Business Transaction' => getEnvVariables('business_transaction'),
                        'Amount'        => $amount,
                        'Text'          => "$id-$short_code-+$msisdn",
                        'Cust Code'     => $short_code,
                        'Business Area' => getEnvVariables('business_area'),
                        'Profit Center' => getEnvVariables('profit_center'),
                        'Posting Date'  => $formattedDate,
                        'Document Date' => $formattedDate
                );

                HandleCSV::SAPFile(getEnvVariables('sap_file'), array($sap_data));
        } catch(Exception $e) {
                logThis(3, "An error occurred: " . $e->getMessage() . "\n" . $e);
        }

        try {
                HandleCSV::transactionsFile($save_transaction_file, array(array($id, $time)));
        } catch(Exception $e) {
                logThis(3, "An error occurred: " . $e->getMessage() . "\n" . $e);
        }

        return true;
}