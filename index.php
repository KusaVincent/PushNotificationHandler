<?php

require_once 'api_login.php';
require_once 'compare_hash.php';
require_once 'vendor/autoload.php';
require_once 'prepare_request.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $response = ['ResultCode' => 1, 'ResultDesc' => 'Invalid request method'];
    echo json_encode($response);
    exit;
}

$postData = file_get_contents('php://input');

if ($postData === false) {
    $response = ['ResultCode' => 1, 'ResultDesc' => 'Unable to get POST data'];
    echo json_encode($response);
    exit;
}

$data = json_decode($postData, true);

if ($data === null || !prepareAPIRequest($data)) {
    $response = ['ResultCode' => 1, 'ResultDesc' => 'Invalid JSON data'];
    echo json_encode($response);
    exit;
} 

$response = ['ResultCode' => 0, 'ResultDesc' => 'Data received successfully'];
echo json_encode($response);