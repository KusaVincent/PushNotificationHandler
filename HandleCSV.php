<?php

class HandleCSV
{
    public static function transactionsFile(string $csvFilePath, array $newData) : void
    {
        $headerArray = array('MpesaRef', 'Timestamp');

        self::writeToFile($csvFilePath, $newData, $headerArray);
    }

    public static function SAPFile(string $csvFilePath, array $newData) : void
    {
        $headerArray = array('MpesaRef', 'Timestamp', 'shortcode', 'mobile');

        self::writeToFile($csvFilePath, $newData, $headerArray);
    }

    public static function readCSV(string $csvFilePath): array
    {
        $csvData = array();

        if (!file_exists($csvFilePath)) throw new Exception("The file does not exist.");

        if (($handle = fopen($csvFilePath, "r")) === false) throw new Exception("Error opening the file.");

        fgetcsv($handle); // Skip the header row

        while (($row = fgetcsv($handle, 1000, ",")) !== false) {
            $csvData[] = $row;
        }

        fclose($handle);

        return $csvData;
    }

    private static function writeToFile(string $csvFilePath, array $newData, array $header): void
    {
        try {
            if (!file_exists($csvFilePath)) {
                $fp = fopen($csvFilePath, 'w');
                if (!$fp) throw new Exception("Failed to open file for writing: $csvFilePath");

                fputcsv($fp, $header);
            } else {
                $fp = fopen($csvFilePath, 'a');

                if (!$fp) throw new Exception("Failed to open file for writing: $csvFilePath");
            }

            foreach ($newData as $row) {
                fwrite($fp, implode(',', $row) . "\n");
            }

            fclose($fp);

            echo "Data appended successfully.";
        } catch (Exception $e) {
            echo "An error occurred: " . $e->getMessage();
        }
    }
}

// Example usage:
HandleCSV::SAPFile('sap\mpesa\C2B.csv', array(
    array('SE29X22D7P', '2024-05-03 08:15:20', '8835670', '2547832130953'),
    array('SE32X62D8Q', '2024-05-03 08:15:20', '8835670', '2547832130953')
));

HandleCSV::transactionsFile('sap\transaction\transactions.csv', array(
    array('SE29X22D7P', '2024-05-03 08:15:20'),
    array('SE32X62D8Q', '2024-05-03 08:15:20')
));