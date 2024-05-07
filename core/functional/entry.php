<?php
require_once __DIR__ .  '/load_files.php';

function api_entry(string $api_type = 'confirmation') {

    $failure_status = 1;
    $success_status = 0;

    $validate        = false;
    $status          = 'ResultCode';
    $description     = 'ResultDesc';
    $failure_message = 'Invalid JSON data';
    $sucess_message  = 'Data received successfully';

    $check_file = getEnvVariables('transaction_file');

    if($api_type == 'validation') {
        $failure_status = 0;
        $success_status = 1;

        $validate        = true;
        $status          = 'STATUS';
        $description     = 'DESCRIPTION';
        $failure_message = 'Validation failed';
        $sucess_message  = 'Validation successful';
        
        $check_file = getEnvVariables('validation_file');
    }

    try {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $response = json_encode([$status => $failure_status, $description => 'Invalid request method']);
    
            logThis(2,  "WRONG_REQUEST_METHOD: " . $response . "\n Request Method: $_SERVER[REQUEST_METHOD]");
    
            echo $response;
            exit;
        }
    
        $postData = file_get_contents('php://input');
    
        logThis(1, "RECEIVED_PAYLOAD: " . $postData);
    
        if (empty($postData) || $postData === false) {
            $response = json_encode([$status => $failure_status, $description => 'Unable to get POST data']);
    
            logThis(2,  "EMPTY_PAYLOAD: " . $response);
    
            echo $response;
            exit;
        }
    
        try {
            $data = json_decode($postData, true);
    
            if ($data === null || !prepareAPIRequest($data, $check_file, $validate)) {
                $response = json_encode([$status => $failure_status, $description => $failure_message]);
    
                logThis(2, $response);
    
                echo $response;
                exit;
            }
    
            $response = json_encode([$status => $success_status, $description => $sucess_message]);
    
            logThis(4,  "REQUEST_SUCCESSFUL: " . $response);
    
            echo $response;
        } catch (Exception $e) {
            logThis(2, "UNHANDLED_EXCEPTION: " . $e->getMessage() . "\n" . $e);
            echo json_encode([$status => $failure_status, $description => 'An error occured']);
        }
    } catch (Exception $e) {
        echo json_encode([$status => $failure_status, $description => 'We faced an error.']);
    }
}