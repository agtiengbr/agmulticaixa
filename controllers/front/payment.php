<?php

class AgMultiCaixaPaymentModuleFrontController extends ModuleFrontController
{
    public function initContent()
    {
        $cart = $this->context->cart;
        if (
            $cart->id_customer == 0
            || $cart->id_address_delivery == 0
            || $cart->id_address_invoice == 0
            || !$this->module->active
        ) {
            Tools::redirect('index.php?controller=order&step=1');
        }

        $pay_method = Tools::getValue('payment_mode');

        $this->module->validateOrder($cart->id, Configuration::get('AGMULTICAIXA_STATE_PENDING_PAYMENT'), $cart->getOrderTotal(), 'Multicaixa Express', NULL, null, (int)$this->context->currency->id, false, $this->context->customer->secure_key);

        $ps_order = new Order(Order::getOrderByCartId($cart->id));

        Tools::redirect('index.php?controller=order-confirmation&do_pay=1&id_cart=' . $ps_order->id_cart . '&id_module=' . $this->module->id . '&id_order=' . $ps_order->id . '&key=' . $this->context->customer->secure_key);
    }
}
