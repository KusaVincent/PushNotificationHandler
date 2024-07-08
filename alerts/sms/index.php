<?php
require_once __DIR__ . '/../../push_api/functional/load_files.php';
require_once SMS_ALERT_PATH . 'SMS.php';

$smsFileEntries = HandleCSV::readCSV(getEnvVariables('msg_arc_file'));

if (empty($smsFileEntries))
    return false;


foreach ($smsFileEntries as $smsFileEntry => $smsFileData) {
    $smsEntry = linearSearch($smsFileData[0], HandleCSV::readCSV(getEnvVariables('sms_file')), true);

    $smsBody = "Payment for $smsEntry[0] of KES $smsEntry[3] received from $smsEntry[1] at $smsEntry[4].Mpesa reference: $smsEntry[2].";

    echo (new SMS($smsFileData[1], $smsBody))->send();
}

// mv sms.csv sms_archive.csv

// php smsindexfile.csv

// rm -rf sms_archive.csv