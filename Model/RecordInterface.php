<?php

namespace DoS\SMSBundle\Model;

use DoS\ResourceBundle\Model\TimestampableInterface;
use DoS\UserBundle\Model\UserAwareInterface;
use libphonenumber\PhoneNumber;

interface RecordInterface extends TimestampableInterface, UserAwareInterface
{
    const STATE_DRAFT = 'draft';
    const STATE_QUEUED = 'queued';
    const STATE_SENT = 'sent';
    const STATE_FAILED = 'failed';
    const STATE_DELIVERED = 'delivered';

    /**
     * @return int
     */
    public function getId();

    /**
     * @return PhoneNumber
     */
    public function getNumber();

    /**
     * @param PhoneNumber $number
     */
    public function setNumber($number);

    /**
     * @return string
     */
    public function getMessage();

    /**
     * @param string $message
     */
    public function setMessage($message);

    /**
     * @return string
     */
    public function getTransactionId();

    /**
     * @param string $transactionId
     */
    public function setTransactionId($transactionId);

    /**
     * @return string
     */
    public function getState();

    /**
     * @param string $state
     */
    public function setState($state);

    /**
     * @return float
     */
    public function getDuration();

    /**
     * @param float $duration
     */
    public function setDuration($duration);

    /**
     * @return bool
     */
    public function isResponded();

    /**
     * @param bool $responded
     */
    public function setResponded($responded);

    /**
     * @param array $data
     * @param $success
     * @param \DateTime|null $dateTime
     */
    public function responded(array $data, $success, \DateTime $dateTime = null);

    /**
     * @return array
     */
    public function getResponder();

    /**
     * @param array $responder
     */
    public function setResponder(array $responder);

    /**
     * @return \DateTime
     */
    public function getRespondedAt();

    /**
     * @param \DateTime $respondedAt
     */
    public function setRespondedAt($respondedAt);

    /**
     * @return float
     */
    public function getPrice();

    /**
     * @param float $price
     */
    public function setPrice($price);

    /**
     * @return string
     */
    public function getCurrency();

    /**
     * @param string $currency
     */
    public function setCurrency($currency);

    /**
     * @return ProviderInterface
     */
    public function getProvider();

    /**
     * @param ProviderInterface $provider
     */
    public function setProvider(ProviderInterface $provider);
}
