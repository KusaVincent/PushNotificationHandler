<?php
require_once __DIR__ . '/../../push_api/functional/load_files.php';
require_once SMS_ALERT_PATH . 'SMS.php';

try {
    $smsFileEntries = HandleCSV::readCSV(getEnvVariables('msg_file'));

    if (empty($smsFileEntries))
        return false;

    foreach ($smsFileEntries as $smsFileEntry => $smsFileData) {
        $smsEntry = linearSearch($smsFileData[0], HandleCSV::readCSV(getEnvVariables('sms_arc_file')), true);

        $smsBody = "Payment for $smsEntry[0] of KES $smsEntry[3] received from $smsEntry[1] at $smsEntry[4].Mpesa reference: $smsEntry[2].";

        logThis(4, (new SMS($smsFileData[1], $smsBody))->send());
    }
} catch (Exception $e) {
    logThis(2, "An error occurred: " . $e->getMessage() . "\n" . $e);
}
// mv sms.csv sms_archive.csv

// php smsindexfile.csv

// rm -rf sms_archive.csv