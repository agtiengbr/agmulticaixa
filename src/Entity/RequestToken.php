<?php

namespace AGTI\MultiCaixa\Entity;

class RequestToken
{
    protected $reference;
    protected $amount;
    protected $token;
    protected $terminal;
    protected $mobile;
    protected $card;
    protected $cssUrl;
    protected $callbackUrl;

    /**
     * Get the value of reference
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * Set the value of reference
     *
     * @return  self
     */
    public function setReference($reference)
    {
        $this->reference = $reference;

        return $this;
    }

    /**
     * Get the value of amount
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set the value of amount
     *
     * @return  self
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get the value of token
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set the value of token
     *
     * @return  self
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get the value of terminal
     */
    public function getTerminal()
    {
        return $this->terminal;
    }

    /**
     * Set the value of terminal
     *
     * @return  self
     */
    public function setTerminal($terminal)
    {
        $this->terminal = $terminal;

        return $this;
    }

    /**
     * Get the value of mobile
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * Set the value of mobile
     *
     * @return  self
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;

        return $this;
    }

    /**
     * Get the value of card
     */
    public function getCard()
    {
        return $this->card;
    }

    /**
     * Set the value of card
     *
     * @return  self
     */
    public function setCard($card)
    {
        $this->card = $card;

        return $this;
    }

    /**
     * Get the value of cssUrl
     */
    public function getCssUrl()
    {
        return $this->cssUrl;
    }

    /**
     * Set the value of cssUrl
     *
     * @return  self
     */
    public function setCssUrl($cssUrl)
    {
        $this->cssUrl = $cssUrl;

        return $this;
    }

    /**
     * Get the value of callbackUrl
     */
    public function getCallbackUrl()
    {
        return $this->callbackUrl;
    }

    /**
     * Set the value of callbackUrl
     *
     * @return  self
     */
    public function setCallbackUrl($callbackUrl)
    {
        $this->callbackUrl = $callbackUrl;

        return $this;
    }
}
