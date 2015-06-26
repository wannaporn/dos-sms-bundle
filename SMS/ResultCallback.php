<?php

namespace DoS\SMSBundle\SMS;

class ResultCallback
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var bool
     */
    protected $success = false;

    /**
     * @var int
     */
    protected $price;

    /**
     * @var array
     */
    protected $data = array();

    public function __construct(array $data = array())
    {
        $this->data = $data;
    }

    /**
     * {@inheritdoc}
     */
    public function getMessageId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function setMessageId($id)
    {
        $this->id = $id;
    }

    /**
     * {@inheritdoc}
     */
    public function isSuccess()
    {
        return $this->success;
    }

    /**
     * {@inheritdoc}
     */
    public function setSuccess($success)
    {
        $this->success = $success;
    }

    /**
     * @return int
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param int $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }
}
