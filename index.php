<?php
require_once 'load_files.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response = json_encode(['ResultCode' => 1, 'ResultDesc' => 'Invalid request method']);

    logThis(2,  "WRONG_REQUEST_METHOD: " . $response . "\n Request Method: $_SERVER[REQUEST_METHOD]");

    echo $response;
    exit;
}

$postData = file_get_contents('php://input');

logThis(1, "RECEIVED_PAYLOAD: " . $postData);

if (empty($postData) || $postData === false) {
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

logThis(4,  "REQUEST_SUCCESSFUL: " . $response);

echo $response;