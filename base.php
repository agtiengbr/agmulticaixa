<?php

use AGTI\MultiCaixa\Entity\RequestToken;
use AGTI\MultiCaixa\Form\Configuration as FConfiguration;
use AGTI\MultiCaixa\Gateway;
use AGTI\Cliente\Presenter\Tab;
use AGTI\Cliente\Presenter\Tabs;
use PrestaShop\PrestaShop\Core\Payment\PaymentOption;
use Configuration as GlobalConfiguration;

require_once _PS_MODULE_DIR_ . 'agcliente/lib/AgPaymentModule.php';

class BaseAgMultiCaixa extends AgPaymentModule
{
    protected $hooks = [
        'displayHeader',
        'displayBackOfficeHeader',
        'paymentOptions',
        'displayPaymentTop',
        'payment',
        'orderConfirmation',
        'displayOrderDetail',
        'displayAdminOrderContentOrder',
        'actionGetAdminOrderButtons',
        'displayCustomerAccount'
    ];
    //menus do administrativo
    protected $main_tab = 'AdminParentModulesSf';
    protected $main_tab_ps16 = 'AdminParentModules';

    protected $tabs = array();

    public function __construct()
    {
        $this->name     = 'agmulticaixa';
        $this->tab      = 'payments_gateways';
        $this->version  = '1.0.6';
        $this->author   = 'AGTI';

        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = 'MultiCaixa Express';
        $this->description = 'Permite que seus clientes comprem em sua loja utilizando o sistema Multicaixa Express.';

        $this->initGateway();
    }

    public function install()
    {
        $models = array(
            'AgMultiCaixaOrders',
            'AgMultiCaixaWebHook'
        );

        foreach ($models as $class) {

            require_once _PS_MODULE_DIR_ . $this->name . '/classes/' . $class . '.php';
            //instantiate the module
            $modelInstance = new $class();
            //create the table relative to this model in the database
            //if the table does not exists yet
            $modelInstance->createDatabase();

            //if the table already exists, add to it any column that may be missing.
            //this is useful in the case of new updates that require new columns
            //to exist in the table.
            $modelInstance->createMissingColumns();

            $modelInstance->createIndexes();
        }

        if (!parent::install()) {
            return false;
        }

        return true;
    }

    public function resetConfig()
    {
        GlobalConfiguration::updateValue('AGMULTICAIXA_STATE_PENDING_PAYMENT', 1);
        GlobalConfiguration::updateValue('AGMULTICAIXA_STATE_REJECTED', 8);
        GlobalConfiguration::updateValue('AGMULTICAIXA_STATE_ACCEPTED', 2);

        $existent_worker_group = AgClienteWorkerGroup::findByName('agmulticaixa_orders');
        if (!Validate::isLoadedObject($existent_worker_group) || (Validate::isLoadedObject($existent_worker_group) && $existent_worker_group->module != 'agcliente')) {
            $workerGroup = new AgClienteWorkerGroup;
            $workerGroup->group_name = 'agmulticaixa_orders';
            $workerGroup->qty_wanted_workers = 1;
            $workerGroup->module = 'agmulticaixa';
            $workerGroup->controller = 'processorders';
            $workerGroup->active = 1;

            $workerGroup->save();
        }
    }

    public function getContent()
    {
        return $this->renderConfigTab();
    }


    private function generateIframe($id_order, $total)
    {
        try {
            $url = $this->context->link->getModuleLink($this->name, 'webhook');

            $request =  new RequestToken();
            $request->setReference($id_order);
            $request->setAmount((float)$total);
            $request->setCard(GlobalConfiguration::get('AGMULTICAIXA_enabled_card') ? 'AUTHORIZATION' : 'DISABLED'); // por enquanto deve ser deixado desabilitado
            $request->setMobile('PAYMENT');
            // $request->setCssUrl(Tools::getProtocol(Tools::usingSecureMode()) . Tools::getHttpHost() . $this->_path . 'views/css/iframe.css');
            $request->setCallbackUrl($url);
            // faz a requisição para receber o token de compra necessário para gerar o webframe
            return Gateway::getPurchaseToken($request);
        } catch (Exception $ex) {
            AgClienteLogger::addLog('agmulticaixa - Erro ao gerar o iframe - ' . $ex->getMessage(), 3, $ex->getCode(), null, null, true);

            return;
        }
    }

    /* Configs */
    protected function renderConfigTab()
    {
        $form = new FConfiguration($this);
        $form->postProcess();
        $tabs = new Tabs;

        $tab = new Tab;
        $tab->setTitle("Autenticação")
            ->setIcon("cogs")
            ->setid("auth")
            ->setBody($form->renderHtml())
            ->setActive(true);
        $tabs->addTab($tab);

        $tab = new Tab;
        $tab->setTitle("Suporte")
            ->setIcon("help")
            ->setId("support")
            ->setBody(agcliente::renderHelpTab($this));

        $tabs->addTab($tab);

        return $tabs->render();
    }

    /* HOOKS */
    public function hookDisplayHeader()
    {
        if ($this->context->controller instanceof OrderConfirmationController) {
            Media::addJsDef(array(
                'id_card' => $this->context->cart->id,
                'url_mcx_order' => $this->context->link->getModuleLink($this->name, 'orders'),
            ));

            $this->context->controller->addJs($this->_path . 'views/js/iframe.js');
            $this->context->controller->addCss($this->_path . 'views/css/iframe.css');
        }
    }

    // até PS 1.6
    public function hookPayment()
    {
        // $iframe = $this->generateIframe();

        // return $iframe;
    }

    public function hookPaymentOptions()
    {
        //cria a opção de pagamento da EMIS e cria o form para a próxima etapa onde será feito o pagamento
        $newOption = new PaymentOption();
        $newOption->setCallToActionText('Pagamento por Multicaixa Express')->setForm($this->generateNextStep());
        $options[] = $newOption;

        return $options;
    }

    public function generateNextStep()
    {
        $this->context->smarty->assign(array(
            'form_action' => $this->context->link->getModuleLink($this->name, 'payment'),
        ));

        return $this->display($this->_path, 'views/templates/front/paymentInfo.tpl');
    }

    public function hookOrderConfirmation($params)
    {
        //se na url tiver o parâmetro do_pay=1, será gerado o iframe para o pagamento
        $do_payment = (int) Tools::getValue('do_pay');
        if ($do_payment === 1) {

            $response = $this->generateIframe($params['order']->id, $params['order']->total_paid);

            if ($response) {
                if (GlobalConfiguration::get('AGMULTICAIXA_sandbox_enabled')) {
                    $url = 'https://cerpagamentonline.emis.co.ao';
                } else {
                    $url = 'https://pagamentonline.emis.co.ao';
                }

                Media::addJsDef(array(
                    'order_id' => $params['order']->id,
                    'base_url' => $url
                ));

                $this->context->smarty->assign(array(
                    'timeout' => $response->getTimeToLive(), // é usada para recarregar a página caso o token expire, sempre que o token expirar a página deve ser recarregada mesmo que esteja em processo de validação
                    'url' => $url . "/online-payment-gateway/portal/frame?token=" . $response->getId(), // url utilizada para criar o webframe dentro do iframe

                ));

                return $this->display(_PS_MODULE_DIR_ . $this->name, 'views/templates/hook/payment.tpl');
            }
        }
    }

    public function hookDisplayOrderDetail($params)
    {
        if ($params['order']->hasBeenPaid() || $params['order']->module != $this->name) {
            return;
        }

        $cart = Cart::getCartByOrderId($params['order']->id);
        if (!Validate::isLoadedObject($cart)) {
            return;
        }

        $payment_link = 'index.php?controller=order-confirmation&do_pay=1&id_cart=' . $cart->id . '&id_module=' . $this->id . '&id_order=' . $params['order']->id . '&key=' . $this->context->customer->secure_key;

        $this->context->smarty->assign([
            'payment_link' => $payment_link
        ]);

        return $this->display(_PS_MODULE_DIR_ . $this->name, 'order_detail.tpl');
    }

    public function initGateway()
    {
        if (GlobalConfiguration::get('AGMULTICAIXA_sandbox_enabled')) {
            $token = GlobalConfiguration::get('AGMULTICAIXA_token_test');
        } else {
            $token = GlobalConfiguration::get('AGMULTICAIXA_token_prod');
        }

        Gateway::setToken($token);
    }
}
