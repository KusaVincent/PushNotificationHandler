<?php
require __DIR__ . '/log_file.php';
require __DIR__ . '..//vendor/autoload.php';

use phpseclib3\Net\SFTP;

class FileTransfer
{
    public function ftpUpload(string $localFile, string $remoteFile, string $hostname, string $username, string $password, ?int $port = null) : bool {
        if($port === null) {
            $ftp = new SFTP($hostname);
        } else {
            $ftp = new SFTP($hostname, $port);
        }

        try {
            if (!$ftp->login($username, $password)) {
                throw new Exception("Failed to login to server");
            }

            if (!$ftp->put($remoteFile, $localFile, SFTP::SOURCE_LOCAL_FILE)) {
                throw new Exception("Failed to upload file to the server");
            }

            return true;
        } catch (Exception $e) {
            logThis(2, "Error: " . $e->getMessage() . "\n $e");
            return false;
        } finally {
            if (isset($ftp)) {
                $ftp->disconnect();
            }
        }
    }
}