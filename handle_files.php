<?php

function handlesTransactionFile($id, $save_transaction_file) : bool {
    try {
            if(checkDuplicates($id, HandleCSV::readCSV($save_transaction_file))) {
                    logThis(1,  "DUPLICATE_DATA: " . 'Passed enrty duplicated');
                    return true;
            }
    } catch(Exception $e) {
            logThis(3, "An error occurred: " . $e->getMessage() . "\n" . $e);
    }

    return false;
}

function writeTransactionFile($id, $time, $save_transaction_file) : void {
    try {
            HandleCSV::transactionsFile($save_transaction_file, array(array($id, $time)));
    } catch(Exception $e) {
            logThis(3, "An error occurred: " . $e->getMessage() . "\n" . $e);
    }  
}

function writeSAPFile($id, $msisdn, $amount, $created_at, $short_code) : void {
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
}