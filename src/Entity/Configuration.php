<?php

namespace AGTI\MultiCaixa\Entity;

class Configuration
{
    protected $token_test;
    protected $sandbox_enabled;
    protected $token_prod;
    protected $enabled_card;
    protected $state_pending_payment;
    protected $state_rejected;
    protected $state_accepted;

    public function persist()
    {
        \Configuration::updateValue('AGMULTICAIXA_STATE_PENDING_PAYMENT', $this->getStatePendingPayment());
        \Configuration::updateValue('AGMULTICAIXA_STATE_REJECTED', $this->getStateRejected());
        \Configuration::updateValue('AGMULTICAIXA_STATE_ACCEPTED', $this->getStateAccepted());
        \Configuration::updateValue('AGMULTICAIXA_enabled_card', $this->getEnabledCard());
        \Configuration::updateValue('AGMULTICAIXA_token_test', $this->getTokenTest());
        \Configuration::updateValue('AGMULTICAIXA_sandbox_enabled', $this->getSandboxEnabled());

        \Configuration::updateValue('AGMULTICAIXA_token_prod', $this->getTokenProd());
    }

    public function loadConfig()
    {
        $config = \Configuration::getMultiple([
            'AGMULTICAIXA_STATE_PENDING_PAYMENT',
            'AGMULTICAIXA_STATE_REJECTED',
            'AGMULTICAIXA_STATE_ACCEPTED',
            'AGMULTICAIXA_enabled_card',
            'AGMULTICAIXA_token_test',
            'AGMULTICAIXA_sandbox_enabled',
            'AGMULTICAIXA_token_prod'
        ]);

        $this->setStatePendingPayment($config['AGMULTICAIXA_STATE_PENDING_PAYMENT']);
        $this->setStateRejected($config['AGMULTICAIXA_STATE_REJECTED']);
        $this->setStateAccepted($config['AGMULTICAIXA_STATE_ACCEPTED']);
        $this->setEnabledCard($config['AGMULTICAIXA_enabled_card']);
        $this->setTokenTest($config['AGMULTICAIXA_token_test']);
        $this->setSandboxEnabled($config['AGMULTICAIXA_sandbox_enabled']);
        $this->setTokenProd($config['AGMULTICAIXA_token_prod']);
    }

    /**
     * Get the value of token_test
     */
    public function getTokenTest()
    {
        return $this->token_test;
    }

    /**
     * Set the value of token_test
     *
     * @return  self
     */
    public function setTokenTest($token_test)
    {
        $this->token_test = $token_test;

        return $this;
    }

    /**
     * Get the value of sandbox_enabled
     */
    public function getSandboxEnabled()
    {
        return $this->sandbox_enabled;
    }

    /**
     * Set the value of sandbox_enabled
     *
     * @return  self
     */
    public function setSandboxEnabled($sandbox_enabled)
    {
        $this->sandbox_enabled = $sandbox_enabled;

        return $this;
    }

    /**
     * Get the value of token_prod
     */
    public function getTokenProd()
    {
        return $this->token_prod;
    }

    /**
     * Set the value of token_prod
     *
     * @return  self
     */
    public function setTokenProd($token_prod)
    {
        $this->token_prod = $token_prod;

        return $this;
    }

    /**
     * Get the value of enabled_card
     */
    public function getEnabledCard()
    {
        return $this->enabled_card;
    }

    /**
     * Set the value of enabled_card
     *
     * @return  self
     */
    public function setEnabledCard($enabled_card)
    {
        $this->enabled_card = $enabled_card;

        return $this;
    }

    /**
     * Get the value of state_pending_payment
     */
    public function getStatePendingPayment()
    {
        return $this->state_pending_payment;
    }

    /**
     * Set the value of state_pending_payment
     *
     * @return  self
     */
    public function setStatePendingPayment($state_pending_payment)
    {
        $this->state_pending_payment = $state_pending_payment;

        return $this;
    }

    /**
     * Get the value of state_rejected
     */
    public function getStateRejected()
    {
        return $this->state_rejected;
    }

    /**
     * Set the value of state_rejected
     *
     * @return  self
     */
    public function setStateRejected($state_rejected)
    {
        $this->state_rejected = $state_rejected;

        return $this;
    }

    /**
     * Get the value of state_accepted
     */
    public function getStateAccepted()
    {
        return $this->state_accepted;
    }

    /**
     * Set the value of state_accepted
     *
     * @return  self
     */
    public function setStateAccepted($state_accepted)
    {
        $this->state_accepted = $state_accepted;

        return $this;
    }
}
