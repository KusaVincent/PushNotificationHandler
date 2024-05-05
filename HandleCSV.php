<?php

class HandleCSV
{
    public static function transactionsFile(string $csvFilePath, array $newData) : void {
        $headerArray = array('MpesaRef', 'Timestamp');

        self::writeToFile($csvFilePath, $newData, $headerArray);
    }

    public static function SAPFile(string $csvFilePath, array $newData) : void {
        $headerArray = array('Business Transaction', 'Amount', 'Text', 'Cust Code', 'Business Area', 'Profit Center', 'Posting Date', 'Document Date');

        self::writeToFile($csvFilePath, $newData, $headerArray);
    }

    public static function readCSV(string $csvFilePath): array {
        $csvData = [];

        try {
            if (!file_exists($csvFilePath))  throw new Exception("The file does not exist: $csvFilePath");

            $handle = fopen($csvFilePath, "r");

            if ($handle === false) throw new Exception("Error opening the file: $handle");

            try {
                fgetcsv($handle);

                while (($row = fgetcsv($handle, 1000, ",")) !== false) {
                    $csvData[] = $row;
                }
            } catch (Exception $e) {
                throw new Exception("Error reading CSV data: " . $e->getMessage());
            } finally {
                fclose($handle);
            }

        } catch (Exception $e) {
            throw new Exception("An error occurred: " . $e->getMessage());
        }

        return $csvData;
    }

    public static function deleteOldEntry(string $csvFilePath) : void {
        $archivePeriod = getEnvVariables('archive_period');

        try {
            $rows = self::readCSV($csvFilePath);
            $oldDate = date('Y-m-d', strtotime("-$archivePeriod days"));

            $filteredRows = array_filter($rows, function($row) use ($oldDate) {
                $date = $row[1];
                return strtotime($date) >= strtotime($oldDate);
            });

            if (!unlink($csvFilePath)) {
                throw new Exception("Failed to delete file: $csvFilePath");
            }

            self::transactionsFile($csvFilePath, $filteredRows);

            logThis(4, "Rows deleted successfully.");
        } catch (Exception $e) {
            logThis(1, "An error occurred: " . $e->getMessage() . $e);
        }
    }

    private static function writeToFile(string $csvFilePath, array $newData, array $header): void {
        $fp = null;

        if($csvFilePath == '') throw new Exception('File name and path cannot be empty');

        try {
            if (!file_exists($csvFilePath) || !filesize($csvFilePath)) {
                $fp = fopen($csvFilePath, 'w');
                if (!$fp) throw new Exception("Failed to open file for writing: $csvFilePath");

                fputcsv($fp, $header);
            } else {
                $fp = fopen($csvFilePath, 'a');

                if (!$fp) throw new Exception("Failed to open file for writing: $csvFilePath");
            }

            foreach ($newData as $row) {
                try {
                    fwrite($fp, implode(',', $row) . "\n");
                } catch (Exception $e) {
                    logThis(3, "An error occurred while writing to the file: " . $e->getMessage() . "\n" . $e);
                    break;
                }
            }

            logThis(4, "Data appended successfully.");
        } catch (Exception $e) {
            logThis(3, "An error occurred: " . $e->getMessage() . "\n" . $e);
        } finally {
            if ($fp) fclose($fp);
        }
    }
}