<?php

namespace DoS\SMSBundle\Model;

use DoS\ResourceBundle\Model\TimestampableInterface;

interface ProviderInterface extends TimestampableInterface
{
    /**
     * @return int
     */
    public function getId();

    /**
     * @return string
     */
    public function getName();

    /**
     * @param string $name
     */
    public function setName($name);

    /**
     * @return string
     */
    public function getEndpoint();

    /**
     * @param string $endpoint
     */
    public function setEndpoint($endpoint);

    /**
     * @return string
     */
    public function getDescription();

    /**
     * @param string $description
     */
    public function setDescription($description);

    /**
     * @return string
     */
    public function getCallbackUrl();

    /**
     * @param string $callbackUrl
     */
    public function setCallbackUrl($callbackUrl);

    /**
     * @return array
     */
    public function getParameters();

    /**
     * @param array $parameters
     */
    public function setParameters($parameters);

    /**
     * @return string
     */
    public function getMethod();

    /**
     * @param string $method
     */
    public function setMethod($method);

    /**
     * @return bool
     */
    public function isActived();

    /**
     * @param bool $actived
     */
    public function setActived($actived);

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
}
