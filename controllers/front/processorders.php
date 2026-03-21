<?php

class AgmulticaixaprocessordersModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        AgClienteLogger::createLogger(_PS_MODULE_DIR_ . 'agmulticaixa/logs/webhook-' . date('Y-m-d') . '.log', 1);

        /** @var AgClienteWorker */
        global $agti_worker;
        $agti_worker = new AgClienteWorker(Tools::getValue('id_agworker'));

        if (!Validate::isLoadedObject($agti_worker)) {
            AgClienteLogger::addLog(sprintf('Worker não encontrado.'));
            exit();
        }

        set_time_limit(0);
        ignore_user_abort(true);

        $agti_worker->save();

        AgClienteLogger::addLog(sprintf('iniciando atualização dos pedidos'));

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

        AgClienteLogger::addLog(sprintf('fim da atualização dos pedidos'));

        exit();
    }
}
