<?php
namespace app\models;
use Flight;

abstract class AbstractDolibarrModel
{
    protected $baseUrl;
    protected $apiKey;

    public function __construct() {
        $this->baseUrl = 'http://localhost/dolibarr-21.0.1/htdocs/api/index.php/';
        $this->apiKey = '673790585a51232cfbfa11ba1d0d6f057343986d';
    }

    // Getters & Setters
    public function getBaseUrl() {
        return $this->baseUrl;
    } public function getApiKey() {
        return $this->apiKey;
    } public function setBaseUrl($url) {
        $this->baseUrl = $url;
        return $this;
    } public function setApiKey($key) {
        $this->apiKey = $key;
        return $this;
    }

    protected function makeRequest($method, $endpoint, $data = null)
    {
        $url = $this->baseUrl . $endpoint;
        $headers = [
            'Accept: application/json',
            'DOLAPIKEY: ' . $this->apiKey,
            'Content-Type: application/json'
        ];

        $curl = curl_init();
        $options = [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_CUSTOMREQUEST => $method,
        ];
        
        if($data !== null) {
            $options[CURLOPT_POSTFIELDS] = json_encode($data);
        }

        curl_setopt_array($curl, $options);
        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            $error = curl_error($curl);
            curl_close($curl);
            return ['error' => $error];
        }

        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);

        return [
            'status' => $httpCode,
            'data' => json_decode($response, true)
        ];
    }
}
