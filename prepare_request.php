<?php
use Dotenv\Dotenv;

Dotenv::createImmutable(__DIR__)->load();

function prepareAPIRequest(array $data) : bool
{
        $save_transaction_file = $_ENV['TRANSACTION_FILE'];
        
        if (!apiLogin($data["Username"], $data["Password"])) {
                logThis(2,  "AUTH_FAILED: " . 'Credentials past for auth are incorrect. Probably a not from the expected source');
                return false;
        }

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
                $sap_data = array(
                        'Business Transaction' => 'CUSTOMER ACCOUNTS',
                        'Amount'        => $amount,
                        'Text'          => "$id-$short_code-$msisdn",
                        'Cust Code'     => $short_code,
                        'Business Area' => 'YN01',
                        'Profit Center' => 'PK00',
                        'Posting Date'  => date('Ymd', strtotime($created_at)),
                        'Document Date' => date('Ymd', strtotime($created_at))
                );

                HandleCSV::SAPFile($_ENV['SAP_FILE'], array($sap_data));
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