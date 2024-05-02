<?php

function writeToFile(string $csvFilePath, array $newData) //: bool
{
    if (!file_exists($csvFilePath)) {
        $header = array('MpesaRef', 'Timestamp');
        $fp = fopen($csvFilePath, 'w');
        fputcsv($fp, $header);
    } else {
        $fp = fopen($csvFilePath, 'a');
    }
    
    foreach ($newData as $row) {
        fwrite($fp, implode(',', $row) . "\n");
    }

    fclose($fp);

    echo "Data appended successfully.";
}

writeToFile('transactions.csv', array(
    array('SE29X62D7P', '2024-05-03 08:15:20'),
    array('SE30X62D8Q', '2024-05-03 08:15:20')
));