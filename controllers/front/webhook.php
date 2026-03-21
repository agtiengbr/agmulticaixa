<?php

class AgmulticaixawebhookModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        if (!file_exists(_PS_MODULE_DIR_ . 'agmulticaixa/logs')) {
            mkdir(_PS_MODULE_DIR_ . 'agmulticaixa/logs', 0777, true);
        }

        if (Tools::getIsSet('proccess')) {
            // if (!$this->module->auth()) {
            //     Logger::addLog("agmulticaixa - Erro de autenticação de licença.", 4);
            //     exit();
            // }

            $this->proccess();
            exit();
        }

        AgClienteLogger::createLogger(_PS_MODULE_DIR_ . 'agmulticaixa/logs/webhook-' . date('Y-m-d') . '.log', 1);
        AgClienteLogger::addLog("Webhook recebido.");

        $obj = new AgMultiCaixaWebHook();
        $obj->processed = 0;
        $obj->raw_data = file_get_contents('php://input');

        AgClienteLogger::addLog("dados recebidos: " . json_encode($obj->raw_data));

        $obj->date_next_processing = date('Y-m-d H:i:s');
        $obj->id_shop = $this->context->shop->id;

        $obj->save();

        $error = Db::getInstance()->getMsgError();
        if (!$error) {
            AgClienteLogger::addLog("Webhook armazenado.");
            echo 1;
        } else {
            AgClienteLogger::addLog("Erro armazenando webhook: {$error}.");
        }

        exit();
    }

    public function proccess()
    {
        /** @var AgClienteWorker */
        global $agti_worker;
        $agti_worker = new AgClienteWorker(Tools::getValue('id_agworker'));

        set_time_limit(0);
        ignore_user_abort(true);

        AgClienteLogger::createLogger(_PS_MODULE_DIR_ . $this->module->name . '/logs/webhook_proccess.txt', 1);
        AgClienteLogger::addLog('Iniciando processamento dos webhooks.');

        while (1) {
            $agti_worker->save();
            $next = AgMultiCaixaWebHook::getNext();

            if (Validate::isLoadedObject($next)) {
                try {
                    AgClienteLogger::addLog("Processando evento {$next->id}.");

                    $next->proccess();
                    $next->processed = 1;

                    $next->save();

                    AgClienteLogger::addLog("Webhook processado.");
                } catch (Exception $e) {
                    Logger::addLog("Erro processado webhook - " . $e->getMessage(), 3, 1, 'AgMultiCaixaWebhook', $next->id, true);
                    AgClienteLogger::addLog("Erro processado webhook - " . $e->getMessage(), 3);

                    $next->qty_tentatives++;
                    $next->date_next_processing = date('Y-m-d H:i:s', time() + 120);

                    $next->save();
                }
            } else {
                sleep(30);
            }
        }
    }
}
