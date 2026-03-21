<?php

namespace AGTI\MultiCaixa\Service;

use AGTI\MultiCaixa\Entity\ResponsePurchaseToken;

class GetToken extends Service
{
    public function execute($token)
    {
        $data_to_server = [
            'token' => $token,
            'reference' => $this->data->getReference(),
            'amount' => $this->data->getAmount(),
            'card' => $this->data->getCard(),
            'mobile' => $this->data->getMobile(),
            'cssUrl' => $this->data->getCssUrl(),
            'callbackUrl' => $this->data->getCallbackUrl(),
        ];

        $response = $this->doRequest('POST', 'frameToken', $data_to_server);

        $parsed_responde = json_decode($response);

        return $parsed_responde;
    }

    public function buildResponseFromAPI($data)
    {
        $response =  new ResponsePurchaseToken();
        $response->setId($data->id);
        $response->setTimeToLive($data->timeToLive);
        // $response->setTimeToLive(date('Y-m-d H:i:s', time() + ($data->timeToLive / 1000)));

        return $response;
    }
}
