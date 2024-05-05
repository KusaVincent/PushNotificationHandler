<?php
require_once 'vendor/autoload.php';

use Dotenv\Dotenv;

Dotenv::createImmutable(__DIR__)->load();

function getEnvVariables(string $envVariable) : string | int
{
    try {
        return getVar($envVariable);
    } catch (Exception $e) {
        logThis(2, "ENVIRONMENT_VARIABLE: " . $e->getMessage() . "\n" . $e);
        throw new Exception("Variable $envVariable not set");
    }
}

function getVar(string $envVariable) : string | int {

    return isset($_ENV[strtoupper($envVariable)]) ? $_ENV[strtoupper($envVariable)] : throw new Exception("Variable $envVariable not set");
}