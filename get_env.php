<?php
require_once 'vendor/autoload.php';

use Dotenv\Dotenv;

Dotenv::createImmutable(__DIR__)->load();

function getEnvVariables(string $envVariable) : string | int
{
    return $_ENV[strtoupper($envVariable)];
}