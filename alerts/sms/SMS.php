<?php
require_once SMS_ALERT_PATH . 'CelcomSMS.php';

class SMS extends CelcomSMS
{
    private $url;
    private $apiKey;
    private $partnerID;
    private $shortCode;
    private array $smsList;

    public function __construct(private string $mobile, private string $message)
    {

        $this->url = getEnvVariables('sms_url');
        $this->apiKey = getEnvVariables('sms_api_key');
        $this->partnerID = getEnvVariables('sms_partner_id');
        $this->shortCode = getEnvVariables('sms_short_code');

        parent::__construct($this->url, $this->prepareData());
    }

    private function prepareData(): string
    {
        $smsList = [
            'partnerID' => $this->partnerID,
            'apikey' => $this->apiKey,
            'pass_type' => 'plain',
            'mobile' => $this->mobile,
            'message' => $this->message,
            'shortcode' => $this->shortCode,
        ];

        $this->smsList = [
            $smsList
        ];

        $postData = [
            'count' => 1,
            'smslist' => $this->smsList
        ];

        return json_encode($postData);
    }
}