<?php
class AgMultiCaixaOrdersModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        try {
            $order_id = Tools::getValue('order_id');
            $gpo_id = Tools::getValue('gpo_id');

            if ($order_id) {
                if (is_string($gpo_id)) {

                    $mcx_order = new AgMultiCaixaOrders();
                    $mcx_order->order_id = $order_id;
                    $mcx_order->gpo_id = $gpo_id;
                    $mcx_order->id_shop = $this->context->shop->id;
                    $mcx_order->state = 1;

                    $mcx_order->save();

                    echo json_encode(['state' => 'success']);
                } else {
                    AgClienteLogger::addLog("agmulticaixa - o id da transação é inválido", 3, 422, null, null, true);

                    AgClienteLogger::createLogger(_PS_MODULE_DIR_ . 'agmulticaixa/logs/webhook.log', 1);
                    AgClienteLogger::addLog("Erro no id da transação: " . json_encode($gpo_id));

                    echo json_encode(['state' => 'erro']);
                }
            } else {
                AgClienteLogger::addLog("agmulticaixa - order_id não informado", 3, 404, null, null, true);

                echo json_encode(['state' => 'erro']);
            }
        } catch (Exception $ex) {
            AgClienteLogger::addLog("agmulticaixa - " . $ex->getMessage(), 3, $ex->getCode(), 'Order', $order_id, true);

            echo json_encode(['state' => 'erro']);
        }

        exit();
    }
}
