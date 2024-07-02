<?php

class CelcomSMS
{
    private $curl;
    private $headers;

    public function __construct(private string $url, private string $postData)
    {
        $this->curl = curl_init();
        $this->headers = array(
            'Content-Type: application/json'
        );

        $this->configureAlert();
    }

    public function send(): string
    {
        $response = curl_exec($this->curl);
        curl_close($this->curl);

        return $response;

        // return $this->postData;
    }

    private function configureAlert(): void
    {
        curl_setopt_array(
            $this->curl,
            array(
                CURLOPT_URL => $this->url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $this->postData,
                CURLOPT_HTTPHEADER => $this->headers,
            )
        );
    }
}