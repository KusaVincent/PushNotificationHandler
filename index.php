<?php
require_once 'vendor/autoload.php';

use Dotenv\Dotenv;

Dotenv::createImmutable(__DIR__)->load();

require_once 'log_file.php';
require_once 'HandleCSV.php';
require_once 'api_login.php';
require_once 'compare_hash.php';
require_once 'check_duplicates.php';
require_once 'prepare_request.php';

// echo apiLogin('Devyani', '6Pm4WXtbeZ00cd7uF8zjwSleQk8');
// exit();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response = json_encode(['ResultCode' => 1, 'ResultDesc' => 'Invalid request method']);

    logThis(2,  "WRONG_REQUEST_METHOD: " . $response);

    echo $response;
    exit;
}

$postData = file_get_contents('php://input');

logThis(1, "RECEIVED_PAYLOAD: " . $postData);

if ($postData === false) {
    $response = json_encode(['ResultCode' => 1, 'ResultDesc' => 'Unable to get POST data']);

    logThis(2,  "EMPTY_PAYLOAD: " . $response);

    echo $response;
    exit;
}

$data = json_decode($postData, true);

if ($data === null || !prepareAPIRequest($data)) {
    $response = json_encode(['ResultCode' => 1, 'ResultDesc' => 'Invalid JSON data']);

    logThis(2, $response);

    echo $response;
    exit;
} 

$response = json_encode(['ResultCode' => 0, 'ResultDesc' => 'Data received successfully']);

logThis(2,  "SUCCESS: " . $response);

echo $response;