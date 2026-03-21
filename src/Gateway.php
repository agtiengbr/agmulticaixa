<?php

namespace AGTI\MultiCaixa;

use AGTI\MultiCaixa\Entity\RequestToken;
use AGTI\MultiCaixa\Service\GetToken;

class Gateway
{
    protected static $token;

    public static function setToken($token)
    {
        self::$token = $token;
    }

    public static function getPurchaseToken(RequestToken $data)
    {
        $token = new GetToken();
        $data->setToken(self::$token);
        $token->data = $data;

        $return = $token->execute(self::$token);

        return $token->buildResponseFromAPI($return);
    }
}
