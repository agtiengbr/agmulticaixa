<?php

namespace AGTI\MultiCaixa\Form;

use AGTI\Cliente\Form\Form;
use AGTI\MultiCaixa\Entity\Configuration as EntityConfiguration;
use HelperForm;
use Tools;

class Configuration extends Form
{
    protected $submitButton = 'agmulticaixa-config';
    public function renderHtml()
    {
        $order_states = \OrderState::getOrderStates(\Context::getContext()->language->id);

        foreach ($order_states as $order_state) {
            $order_states_for_select[] = [
                'id' => $order_state['id_order_state'],
                'name' => $order_state['name']
            ];
        }

        $inputs_test = [
            'agmulticaixa_token_test' => array(
                'type'  => 'text',
                'label' => 'Token da conta',
                'name'  => 'agmulticaixa_token_test',
                'col' => 3
            ),
            'agmulticaixa_sandbox_enabled' => array(
                'label' => 'Ativar sandbox',
                'type' => 'switch',
                'name' => 'agmulticaixa_sandbox_enabled',
                'values' => [
                    [
                        'id' => 'agmulticaixa_sandbox_enabled_on',
                        'value' => 1,
                        'label' => 'Sim'
                    ],
                    [
                        'id' => 'agmulticaixa_sandbox_enabled_off',
                        'value' => 0,
                        'label' => 'Não'
                    ]
                ]
            )
        ];

        $inputs_prod = [
            'agmulticaixa_token_prod' => array(
                'type'  => 'text',
                'label' => 'Token da conta',
                'name'  => 'agmulticaixa_token_prod',
                'col' => 3
            )
        ];

        $inputs_mapping = [
            'agmulticaixa_state_pending_payment' => array(
                'type'  => 'select',
                'label' => 'Aguardando Pagamento',
                'name'  => 'agmulticaixa_state_pending_payment',
                'col' => 9,
                'options' => [
                    'name' => 'name',
                    'id' => 'id',
                    'query' => $order_states_for_select
                ]
            ),
            'agmulticaixa_state_rejected' => array(
                'type'  => 'select',
                'label' => 'Erro no Pagamento',
                'name'  => 'agmulticaixa_state_rejected',
                'col' => 9,
                'options' => [
                    'name' => 'name',
                    'id' => 'id',
                    'query' => $order_states_for_select
                ]
            ),
            'agmulticaixa_state_accepted' => array(
                'type'  => 'select',
                'label' => 'Pagamento Aprovado',
                'name'  => 'agmulticaixa_state_accepted',
                'col' => 9,
                'options' => [
                    'name' => 'name',
                    'id' => 'id',
                    'query' => $order_states_for_select
                ]
            )
        ];

        $forms = [
            [
                'form' => [
                    'legend' => ['title' => 'Ambiente de testes'],
                    'input' => $inputs_test,
                    'submit' => ['title' => 'Salvar', 'name' => $this->submitButton]
                ]
            ],
            [
                'form' => [
                    'legend' => ['title' => 'Ambiente de produção'],
                    'input' => $inputs_prod,
                    'submit' => ['title' => 'Salvar', 'name' => $this->submitButton]
                ]
            ],
            [
                'form' => [
                    'legend' => ['title' => 'Mapeamentos'],
                    'input' => $inputs_mapping,
                    'submit' => ['title' => 'Salvar', 'name' => $this->submitButton]
                ]
            ]
        ];

        $form = $this->getHelperForm();
        $this->fillForm($form);

        return $form->generateForm($forms);
    }

    public function postProcess()
    {
        if (Tools::isSubmit($this->submitButton)) {
            $this->persistData();
        }
    }

    public function fillForm(HelperForm $form)
    {
        $config = new EntityConfiguration;
        $config->loadConfig();

        $form->fields_value['agmulticaixa_token_test'] = $config->getTokenTest();
        $form->fields_value['agmulticaixa_sandbox_enabled'] = $config->getSandboxEnabled();

        $form->fields_value['agmulticaixa_token_prod'] = $config->getTokenProd();

        $form->fields_value['agmulticaixa_state_pending_payment'] = $config->getStatePendingPayment();
        $form->fields_value['agmulticaixa_state_rejected'] = $config->getStateRejected();
        $form->fields_value['agmulticaixa_state_accepted'] = $config->getStateAccepted();
    }

    public function persistData()
    {
        $config = new EntityConfiguration;

        $config->setTokenTest(Tools::getValue('agmulticaixa_token_test'));
        $config->setSandboxEnabled(Tools::getValue('agmulticaixa_sandbox_enabled'));

        $config->setTokenProd(Tools::getValue('agmulticaixa_token_prod'));

        $config->setStatePendingPayment(Tools::getValue('agmulticaixa_state_pending_payment'));
        $config->setStateRejected(Tools::getValue('agmulticaixa_state_rejected'));
        $config->setStateAccepted(Tools::getValue('agmulticaixa_state_accepted'));

        $config->persist();
    }
}
