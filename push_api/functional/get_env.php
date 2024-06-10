<?php
require_once ROOT_PATH . 'vendor/autoload.php';

use Dotenv\Dotenv;

Dotenv::createImmutable(__DIR__)->load();

function getEnvVariables(string $envVariable) : string | int
{
    try {
        if(str_contains($envVariable, '_file')) {
            $file_path =  PUBLIC_PATH . getVar($envVariable);
            $directoryPath = dirname($file_path);

            if (file_exists($directoryPath) && is_dir($directoryPath)) {
                return $file_path;
            }

            throw new Exception("Variable $envVariable not set");
        }

        return getVar($envVariable);
    } catch (Exception $e) {
        if ($envVariable !== 'log_file')
            logThis(2, "ENVIRONMENT_VARIABLE: " . $e->getMessage() . "\n" . $e);
        throw new Exception("Variable $envVariable not set");
    }
}

function getVar(string $envVariable) : string | int
{
    return isset($_ENV[strtoupper($envVariable)]) ? $_ENV[strtoupper($envVariable)] : throw new Exception("Variable $envVariable not set");
}