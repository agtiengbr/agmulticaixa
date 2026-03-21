<?php
require_once _PS_MODULE_DIR_ . 'agcliente/lib/AgObjectModel.php';

class AgMultiCaixaOrders extends AgObjectModel
{
    public static $definition = [
        'table'     => 'agmulticaixa_orders',
        'primary'   => 'id_agmulticaixa_order',
        'multilang' => false,
        'fields'    => [
            'id_agmulticaixa_order'  => ['type' => self::TYPE_INT,    'db_type' => 'int',         'validate' => 'isInt'],
            'order_id'               => ['type' => self::TYPE_INT,    'db_type' => 'int',         'validate' => 'isInt'],
            'cart_id'                => ['type' => self::TYPE_INT,    'db_type' => 'int',         'validate' => 'isInt'],
            'gpo_id'                 => ['type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'db_type' => 'varchar(256)'],
            'id_shop' => ['type' => self::TYPE_INT, 'db_type' => 'int'],
            'state' => ['type' => self::TYPE_INT, 'db_type' => 'int'],
            'payment_processed' => ['type' => self::TYPE_BOOL, 'db_type' => 'boolean', 'validate' => 'isBool', 'default' => '0'],
            'date_add' => ['type' => self::TYPE_DATE, 'db_type' => 'datetime'],
            'date_upd' => ['type' => self::TYPE_DATE, 'db_type' => 'datetime']
        ]
    ];

    public $id_agmulticaixa_order;
    public $order_id;
    public $cart_id;
    public $gpo_id;
    public $id_shop;
    public $state;
    public $payment_processed;
    public $date_add;
    public $date_upd;

    public static function getByIdGPO($gpo_id)
    {
        $sql = new DbQuery();
        $sql->from('agmulticaixa_orders');
        $sql->where('gpo_id="' . $gpo_id . '"');

        $db_data = Db::getInstance()->getRow($sql);
        if (!$db_data) {
            $db_data = array();
        }

        $return = new AgMultiCaixaOrders();
        $return->hydrate($db_data);

        return $return;
    }
}
