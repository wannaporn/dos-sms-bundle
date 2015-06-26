<?php

namespace DoS\SMSBundle\SMS;

use DoS\SMSBundle\Provider\RecordProvider;
use SmsSender\Provider\ProviderInterface as BaseProviderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

interface ProviderInterface extends BaseProviderInterface
{
    /**
     * @param array $options
     */
    public function applyOptions(array $options = array());

    /**
     * @param array $options
     */
    public function validateOptions(array $options);

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver);

    /**
     * @param ProviderCallbackInterface|null $callback
     */
    public function setCallback(ProviderCallbackInterface $callback = null);

    /**
     * @return ResultCallback[]
     */
    public function getCallbackResults();

    /**
     * @param array $response
     */
    public function processCallback(array $response);

    /**
     * @param RecordProvider $visitor
     */
    public function accept(RecordProvider $visitor);
}
