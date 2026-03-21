<?php

namespace AGTI\MultiCaixa\Entity;

class ResponsePurchaseToken
{
    protected $id;
    protected $timeToLive;

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
     * Get the value of timeToLive
     */
    public function getTimeToLive()
    {
        return $this->timeToLive;
    }

    /**
     * Set the value of timeToLive
     *
     * @return  self
     */
    public function setTimeToLive($timeToLive)
    {
        $this->timeToLive = $timeToLive;

        return $this;
    }
}
