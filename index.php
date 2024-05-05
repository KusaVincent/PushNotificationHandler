<?php
require_once 'load_files.php';

try {
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

    try {
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
    } catch (Exception $e) {
        logThis(2, "UNHANDLED_EXCEPTION: " . $e->getMessage() . "\n" . $e);
        echo json_encode(['ResultCode' => 1, 'ResultDesc' => 'An error occured']);
    }
} catch (Exception $e) {
    echo json_encode(['ResultCode' => 1, 'ResultDesc' => 'We faced an error.']);
}
