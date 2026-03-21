<?php
require_once _PS_MODULE_DIR_ . 'agcliente/lib/AgObjectModel.php';

class AgMultiCaixaWebHook extends AgObjectModel
{
    public static $definition = array(
        'table' => 'agmulticaixa_webhook',
        'primary' => 'id_agmulticaixa_webhook',
        'multilang' => false,
        'fields' => array(
            'id_agmulticaixa_webhook' => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'),
            'raw_data' => array('type' => self::TYPE_STRING, 'db_type' => 'text'),
            'qty_processed' => array('type' => self::TYPE_INT, 'db_type' => 'int'),
            'date_add' => array('type' => self::TYPE_DATE, 'db_type' => 'datetime'),
            'date_upd' => array('type' => self::TYPE_DATE, 'db_type' => 'datetime'),
            'date_next_processing' => array('type' => self::TYPE_DATE, 'db_type' => 'datetime'),
            'processed' => array('type' => self::TYPE_BOOL, 'db_type' => 'bool'),
            'id_shop' => array('type' => self::TYPE_INT, 'db_type' => 'int'),
        ),
        'indexes' => [
            [
                'fields' => ['id_shop', 'processed'],
                'name' => 'processed'
            ]
        ]
    );

    public $id_agmulticaixa_webhook;
    public $raw_data;
    public $qty_processed;
    public $date_add;
    public $date_upd;
    public $date_next_processing;
    public $processed;
    public $id_shop;

    /**
     * @return AgMultiCaixaWebHook|null
     */
    public static function getNext()
    {
        $collection = new PrestaShopCollection('AgMultiCaixaWebHook');
        $collection->where('processed', '=', 0);
        $collection->where('date_next_processing', '<', date('Y-m-d H:i:s'));
        $collection->where('id_shop', '=', Context::getContext()->shop->id);
        $collection->orderBy('date_next_processing', 'ASC');

        return $collection->getFirst();
    }

    public function proccess()
    {
        $data = json_decode($this->raw_data, true);

        if ($data) {
            //busca o pedido que foi enviado como referência na criação do token
            $order = new Order($data['reference']['id']);
            if (!Validate::isLoadedObject($order)) {
                return;
            }

            //busca a moeda que da resposta do callback e atualiza o pedido
            $id_currency = Currency::getIdByIsoCode($data['currency']);

            $order->id_currency = $id_currency;

            //procura a transação relacionada ao pedido na tabela agmulticaixa_orders
            $mc_order = AgMultiCaixaOrders::getByIdGPO($data['id']);
            if (!Validate::isLoadedObject($mc_order)) {
                return;
            }

            //atualiza a transação
            $mc_order->update();

            //atualiza o state da transação na tabela agmulticaixa_orders

            $state = \Configuration::get('AGMULTICAIXA_STATE_' . $data['status']);

            //se o pagamento não foi processado ainda ou se o state recebido é diferente do state atual
            if (!$mc_order->payment_processed || (int)$state != (int)$mc_order->state) {
                //atualiza o state da transação
                $mc_order->state = $state;

                if ((int) $state === (int) \Configuration::get('AGMULTICAIXA_STATE_ACCEPTED')) {
                    Logger::addLog("agmulticaixa - Pedido {$order->id} PAGO.", 1, null, null, null, true);
                } else {
                    Logger::addLog("agmulticaixa - O pagamento do pedido {$order->id} não foi confirmado - " . $data['status'], 1, null, 'Order', $order->id, true);
                }

                //checa se o state recebido é diferente do state atual do pedido
                if ($state != $order->current_state) {
                    $history = $order->getHistory(Context::getContext()->language->id, $state);

                    if (!$history) {
                        $order->setCurrentState($state);
                    }
                }

                $mc_order->payment_processed = true;
                $mc_order->update();
            }
        }
    }
}
