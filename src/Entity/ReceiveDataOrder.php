<?php

namespace AGTI\MultiCaixa\Entity;

class ReceiveDataOrder
{
    protected $creationDate;
    protected $updatedDate;
    protected $id;
    protected $amount;
    protected $clearingPeriod;
    protected $transactionNumber;
    protected $state;
    protected $transactionType;
    protected $orderOrigin;
    protected $currency;
    protected $pointOfSale;
    protected $reference;
    protected $merchantReferenceNumber;
    protected $parentTransaction;
    protected $errorCode;
    protected $errorType;
    protected $errorMessage;

    /**
     * Get the value of creationDate
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Set the value of creationDate
     *
     * @return  self
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get the value of updatedDate
     */
    public function getUpdatedDate()
    {
        return $this->updatedDate;
    }

    /**
     * Set the value of updatedDate
     *
     * @return  self
     */
    public function setUpdatedDate($updatedDate)
    {
        $this->updatedDate = $updatedDate;

        return $this;
    }

    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id)
    {
        $this->id = $id;

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
     * Get the value of clearingPeriod
     */
    public function getClearingPeriod()
    {
        return $this->clearingPeriod;
    }

    /**
     * Set the value of clearingPeriod
     *
     * @return  self
     */
    public function setClearingPeriod($clearingPeriod)
    {
        $this->clearingPeriod = $clearingPeriod;

        return $this;
    }

    /**
     * Get the value of transactionNumber
     */
    public function getTransactionNumber()
    {
        return $this->transactionNumber;
    }

    /**
     * Set the value of transactionNumber
     *
     * @return  self
     */
    public function setTransactionNumber($transactionNumber)
    {
        $this->transactionNumber = $transactionNumber;

        return $this;
    }

    /**
     * Get the value of state
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set the value of state
     *
     * @return  self
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get the value of transactionType
     */
    public function getTransactionType()
    {
        return $this->transactionType;
    }

    /**
     * Set the value of transactionType
     *
     * @return  self
     */
    public function setTransactionType($transactionType)
    {
        $this->transactionType = $transactionType;

        return $this;
    }

    /**
     * Get the value of orderOrigin
     */
    public function getOrderOrigin()
    {
        return $this->orderOrigin;
    }

    /**
     * Set the value of orderOrigin
     *
     * @return  self
     */
    public function setOrderOrigin($orderOrigin)
    {
        $this->orderOrigin = $orderOrigin;

        return $this;
    }

    /**
     * Get the value of currency
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set the value of currency
     *
     * @return  self
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Get the value of pointOfSale
     */
    public function getPointOfSale()
    {
        return $this->pointOfSale;
    }

    /**
     * Set the value of pointOfSale
     *
     * @return  self
     */
    public function setPointOfSale($pointOfSale)
    {
        $this->pointOfSale = $pointOfSale;

        return $this;
    }

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
     * Get the value of merchantReferenceNumber
     */
    public function getMerchantReferenceNumber()
    {
        return $this->merchantReferenceNumber;
    }

    /**
     * Set the value of merchantReferenceNumber
     *
     * @return  self
     */
    public function setMerchantReferenceNumber($merchantReferenceNumber)
    {
        $this->merchantReferenceNumber = $merchantReferenceNumber;

        return $this;
    }

    /**
     * Get the value of parentTransaction
     */
    public function getParentTransaction()
    {
        return $this->parentTransaction;
    }

    /**
     * Set the value of parentTransaction
     *
     * @return  self
     */
    public function setParentTransaction($parentTransaction)
    {
        $this->parentTransaction = $parentTransaction;

        return $this;
    }

    /**
     * Get the value of errorCode
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * Set the value of errorCode
     *
     * @return  self
     */
    public function setErrorCode($errorCode)
    {
        $this->errorCode = $errorCode;

        return $this;
    }

    /**
     * Get the value of errorType
     */
    public function getErrorType()
    {
        return $this->errorType;
    }

    /**
     * Set the value of errorType
     *
     * @return  self
     */
    public function setErrorType($errorType)
    {
        $this->errorType = $errorType;

        return $this;
    }

    /**
     * Get the value of errorMessage
     */
    public function getErrorMessage()
    {
        return $this->errorMessage;
    }

    /**
     * Set the value of errorMessage
     *
     * @return  self
     */
    public function setErrorMessage($errorMessage)
    {
        $this->errorMessage = $errorMessage;

        return $this;
    }
}
