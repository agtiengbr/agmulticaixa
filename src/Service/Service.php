<?php

namespace AGTI\MultiCaixa\Service;

use AGTI\MultiCaixa\Communicator;

abstract class Service extends Communicator
{
    protected $apiPath;

    public function setApiPath($apiPath)
    {
        $this->apiPath = $apiPath;
    }

    /**
     * dispara a requisição à API. Provavelmente vamos precisar adicionar argumentos a essa função
     * (método, body, etc). Deixei o método abstrato porque cada serviço irá retornar uma classe
     * diferente. A implementação será basicamente a mesma.
     **/
    abstract public function execute($token);

    //deve retornar um objeto do tipo Entity
    abstract public function buildResponseFromAPI($data);
}
