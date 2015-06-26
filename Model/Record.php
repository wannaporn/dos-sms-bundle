<?php

namespace DoS\SMSBundle\Model;

use libphonenumber\PhoneNumber;
use Sylius\Component\User\Model\UserInterface;

class Record implements RecordInterface
{
    use TimestampTrait;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var PhoneNumber
     */
    protected $number;

    /**
     * @var string;
     */
    protected $message;

    /**
     * @var string;
     */
    protected $transactionId;

    /**
     * @var string
     */
    protected $state = self::STATE_DRAFT;

    /**
     * @var float
     */
    protected $duration = 0;

    /**
     * @var float
     */
    protected $price = 0;

    /**
     * @var string
     */
    protected $currency = 'THB';

    /**
     * @var bool
     */
    protected $responded = false;

    /**
     * @var array
     */
    protected $responder = array();

    /**
     * @var \DateTime
     */
    protected $respondedAt;

    /**
     * @var ProviderInterface
     */
    protected $provider;

    /**
     * @var UserInterface
     */
    protected $user;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return PhoneNumber
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param PhoneNumber $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * @param string $transactionId
     */
    public function setTransactionId($transactionId)
    {
        $this->transactionId = $transactionId;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }

    /**
     * @return float
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param float $duration
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    }

    /**
     * @return bool
     */
    public function isResponded()
    {
        return $this->responded;
    }

    /**
     * @param bool $responded
     */
    public function setResponded($responded)
    {
        $this->responded = $responded;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * @return array
     */
    public function getResponder()
    {
        return $this->responder;
    }

    /**
     * @param array $responder
     */
    public function setResponder(array $responder)
    {
        $this->responder = $responder;
    }

    /**
     * @return \DateTime
     */
    public function getRespondedAt()
    {
        return $this->respondedAt;
    }

    /**
     * @param \DateTime $respondedAt
     */
    public function setRespondedAt($respondedAt)
    {
        $this->respondedAt = $respondedAt;
    }

    /**
     * @return ProviderInterface
     */
    public function getProvider()
    {
        return $this->provider;
    }

    /**
     * @param ProviderInterface $provider
     */
    public function setProvider(ProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    /**
     * {@inheritdoc}
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * {@inheritdoc}
     */
    public function setUser(UserInterface $user = null)
    {
        $this->user = $user;
    }

    /**
     * {@inheritdoc}
     */
    public function responded(array $data, $success, \DateTime $dateTime = null)
    {
        $this->responder = $data;
        $this->responded = true;
        $this->respondedAt = $dateTime ?: new \DateTime();
        $this->state = $success ? self::STATE_DELIVERED : self::STATE_FAILED;
    }
}
