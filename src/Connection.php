<?php

namespace LKnijnik\Asaas;

use Exception;
use stdClass;

class Connection
{
    public $http;
    public $api_key;
    public $api_status;
    public $base_url;
    public $ssl;
    public $headers;

    public function __construct($token, $status, $ssl)
    {
        if ($status == 'producao' || $status = 'production') {
            $this->api_status = false;
        } elseif ($status == 'homologacao' || $status == 'sandbox') {
            $this->api_status = 1;
        } else {
            die('Tipo de homologação invalida');
        }
        $this->api_key = $token;
        $this->base_url = "https://" . (($this->api_status) ? 'api-sandbox' : 'api').'.asaas.com';

        $this->ssl = $ssl;

        return $this;
    }


    public function get($url, $option = false, $custom = false)
    {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->base_url . '/v3' . $url . $option);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown');

            if (!$this->ssl) curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            if (empty($this->headers)) {
                $this->headers = array(
                    "Content-Type: application/json",
                    "access_token: " . $this->api_key
                );
            }
            if (!empty($custom)) {
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $custom);
            }

            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);

            $response = curl_exec($ch);
            $err = curl_error($ch);
            
            curl_close($ch);

            if ($err) {
                throw new Exception($err);
            }

            $response = json_decode($response) ? json_decode($response) : $response;

            if (empty($response)) {
                throw new Exception('Empty response');
            }

            return $response;

        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }

    }

    private function requestApi($metodo = 'POST', $url = '', ?array $params = [], $json = true, $raw = 1)
    {

    }

    public function post($url, $params)
    {
        try {
            $params = json_encode($params);
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $this->base_url . '/v3' . $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);

            curl_setopt($ch, CURLOPT_POST, TRUE);

            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

            curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown');

            if (!$this->ssl) curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Content-Type: application/json",
                "access_token: " . $this->api_key
            ));


            $response = curl_exec($ch);
            $err = curl_error($ch);
            
            curl_close($ch);

            if ($err) {
                throw new Exception($err);
            }

            $response = json_decode($response) ? json_decode($response) : $response;

            if (empty($response)) {
                throw new Exception('Empty response');
            }

            return $response;

        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function put($url, $params)
    {
        try {
            $params = json_encode($params);
            $ch = curl_init();

            curl_setopt_array($ch, array(
                CURLOPT_URL => $this->base_url . '/v3' . $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown',
                CURLOPT_CUSTOMREQUEST => 'PUT',
                CURLOPT_POSTFIELDS => $params,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/json',
                    'Accept: application/json',
                    'access_token: ' . $this->api_key
                ),
            ));

            if (!$this->ssl) curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $response = curl_exec($ch);
            $err = curl_error($ch);
            
            curl_close($ch);

            if ($err) {
                throw new Exception($err);
            }

            $response = json_decode($response) ? json_decode($response) : $response;

            if (empty($response)) {
                throw new Exception('Empty response');
            }

            return $response;

        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    public function delete($url)
    {
        try {
            $ch = curl_init();

            curl_setopt_array($ch, array(
                CURLOPT_URL => $this->base_url . '/v3' . $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_USERAGENT => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown',
                CURLOPT_CUSTOMREQUEST => 'DELETE',
                CURLOPT_HTTPHEADER => array(
                    'Accept: application/json',
                    'access_token: '. $this->api_key
                ),
            ));

            if (!$this->ssl) curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $response = curl_exec($ch);
            $err = curl_error($ch);
            
            curl_close($ch);

            if ($err) {
                throw new Exception($err);
            }

            $response = json_decode($response) ? json_decode($response) : $response;

            if (empty($response)) {
                throw new Exception('Empty response');
            }

            return $response;

        } catch (Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
}
