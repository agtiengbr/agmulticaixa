<?php

namespace AGTI\MultiCaixa;

use MCEMissingArgumentsRequestException;
use MCEMissingTokenException;
use MCEResponseException;
use Configuration as GlobalConfiguration;
use Exception;

class Communicator
{
    protected $token;
    protected $api_base;

    public function __construct()
    {
        if (!function_exists('curl_init')) {
            throw new \Exception('MultiCaixa: cURL library is required.');
        }
        // $this->api_base = 'https://cerpagamentonline.emis.co.ao/online-payment-gateway/portal/';

        if (GlobalConfiguration::get('AGMULTICAIXA_sandbox_enabled')) {
            $this->api_base = 'https://cerpagamentonline.emis.co.ao/online-payment-gateway/portal/';
        } else {
            $this->api_base = 'https://pagamentonline.emis.co.ao/online-payment-gateway/portal/';
        }

        return $this;
    }

    protected function doRequest($method, $resource, $data)
    {
        if (!$method) {
            throw new \Exception('Tipo de requisição não informada ao realizar requisição.');
        }

        if (!$data['token']) {
            throw new \Exception('Token não informado.');
        }

        $resp = [];
        $postFields = '';
        $contentLength = 0;
        $methodOptions = [];
        $options = [];

        $url = $this->api_base . $resource;

        if (strtoupper($method) === 'POST') {
            $postFields = json_encode($data);
            $contentLength = "Content-length: " . strlen($postFields);
            $methodOptions = array(
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $postFields,
            );
        } else {
            $contentLength = null;
            $methodOptions = array(
                CURLOPT_HTTPGET => true
            );

            $data = http_build_query((array) $data);
            $url = $url . "?" . $data;
        }

        $options = array(
            CURLOPT_HTTPHEADER => array(
                "Content-Type:application/json",
                "Accept:application/json",
                $contentLength,
            ),
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_CONNECTTIMEOUT => 0,
            CURLOPT_TIMEOUT => 45
        );

        $options = ($options + $methodOptions);


        $curl = curl_init();
        curl_setopt_array($curl, $options);
        $resp['body'] = curl_exec($curl);
        $info = curl_getinfo($curl);
        $error = curl_errno($curl);
        $errorMessage = curl_error($curl);
        curl_close($curl);

        if (!$resp['body']) {
            $e = new Exception('Resposta do MultiCaixa Inválida: ' . $resp['body'] . ' - dados enviados: ' . json_encode($data));
            $e->http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

            throw $e;
        }

        $resp_decode = json_decode($resp['body']);

        if ((isset($resp_decode->status) && $resp_decode->status >= 400) || $info['http_code'] >= 400) {
            $msg = $resp_decode;
            $state_code = $resp_decode >= 400 ? $resp_decode : $info['http_code'];

            $msg_error = '';

            if (isset($msg->message)) {
                $msg_error .= $msg->message;
            }

            if (isset($msg->error)) {
                if (is_string($msg->error)) {
                    $msg_error .= $msg->error;
                } else {
                    $msg_error .= json_encode($msg->error);
                }
            }

            if (isset($msg->errors)) {
                $msg_error .= json_encode($msg->errors);
            }

            if ($msg_error == '') {
                $msg_error = $msg;
            }

            if (!is_string($msg_error)) {
                $msg_error = json_encode($msg_error);
            }

            $msg_error .= '(';
            foreach ($msg->result as $item) {
                $msg_error .= $item;
            }
            $msg_error .= ')';

            throw new \Exception("Erro acessando recurso {$resource}. Código de retorno: {$state_code}, mensagem: $msg_error");
        }

        return $resp['body'];
    }
}
