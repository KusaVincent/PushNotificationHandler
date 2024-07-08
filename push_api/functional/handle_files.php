<?php

function handlesTransactionFile($id, $save_transaction_file): bool
{
        try {
                if (checkDuplicates($id, HandleCSV::readCSV($save_transaction_file))) {
                        logThis(1, "DUPLICATE_DATA_CHECKER: " . 'Passed entry found');
                        return true;
                }
        } catch (Exception $e) {
                logThis(3, "An error occurred: " . $e->getMessage() . "\n" . $e);
        }

        return false;
}

function writeSMSFile(array $data, $save_sms_file): void
{
        try {
                HandleCSV::smsFile($save_sms_file, array($data));
        } catch (Exception $e) {
                logThis(3, "An error occurred: " . $e->getMessage() . "\n" . $e);
                throw new Exception($e);
        }
}

function writeTransactionFile($id, $time, $save_transaction_file): void
{
        try {
                HandleCSV::transactionsFile($save_transaction_file, array(array($id, $time)));
        } catch (Exception $e) {
                logThis(3, "An error occurred: " . $e->getMessage() . "\n" . $e);
                throw new Exception($e);
        }
}

function writeSAPFile($id, $name, $amount, $created_at, $short_code): void
{
        try {
                $formattedDate = date('Ymd', strtotime($created_at));

                $sap_data = array(
                        'Business Transaction' => getEnvVariables('business_transaction'),
                        'Amount' => $amount,
                        'Text' => "$id-$short_code-$name",
                        'Cust Code' => $short_code,
                        'Business Area' => getEnvVariables('business_area'),
                        'Profit Center' => getEnvVariables('profit_center'),
                        'Posting Date' => $formattedDate,
                        'Document Date' => $formattedDate
                );

                HandleCSV::SAPFile(getEnvVariables('sap_file'), array($sap_data));
        } catch (Exception $e) {
                logThis(3, "An error occurred: " . $e->getMessage() . "\n" . $e);
                throw new Exception($e);
        }
}