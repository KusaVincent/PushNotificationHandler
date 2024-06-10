<?php
require_once EMAIL_ALERT_PATH . 'Email.php';

class EmailHelper extends Email {

    public function __construct()
    {
        parent::__construct(getEnvVariables('email_sender'), getEnvVariables('email_password'), $this->config());
    }

    private function config() : array
    {
        return [
            'host'   => getEnvVariables('email_host'),
            'port'   => getEnvVariables('email_port'),
            'secure' => getEnvVariables('email_secure')
        ];
    }
}