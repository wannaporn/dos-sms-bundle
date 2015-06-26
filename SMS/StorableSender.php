<?php

namespace DoS\SMSBundle\SMS;

use DoS\SMSBundle\Provider\RecordProvider;
use SmsSender\DelayedSenderInterface;
use SmsSender\SmsSender;
use SmsSender\SmsSenderInterface;

class StorableSender implements DelayedSenderInterface
{
    /**
     * @var RecordProvider
     */
    protected $recordProvider;

    /**
     * @var SmsSenderInterface
     */
    protected $sender;

    /**
     * @var Logger
     */
    protected $logger;

    public function __construct(RecordProvider $recordProvider, SmsSender $sender, Logger $logger = null)
    {
        $this->recordProvider = $recordProvider;
        $this->sender = $sender;
        $this->logger = $logger;
    }

    /**
     * {@inheritDoc}
     */
    public function flush()
    {
        if ($this->sender instanceof DelayedSenderInterface) {
            list($sentMessages, $errors) = $this->sender->flush();

            foreach ($errors as $error) {
                $this->logger->logError($error, $this->getProviderClass());
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function send($recipient, $body, $originator = '')
    {
        $this->activateProvider();

        if ($this->logger) {
            $time = microtime(true);
            $result = $this->sender->send($recipient, $body, $originator);
            $duration = microtime(true) - $time;

            $this->logger->logMessage($result, $duration, $this->getProviderClass());
        } else {
            $result = $this->sender->send($recipient, $body, $originator);
        }

        $this->recordProvider->storeResult($result);

        return $result;
    }

    /**
     * Activate default provider.
     */
    public function activateProvider()
    {
        $provider = $this->recordProvider->getProvider()->getActivedProvider();
        $this->using($provider->getName());
    }

    /**
     * @param $name
     *
     * @return $this
     */
    public function using($name)
    {
        $this->sender->using($name);

        $pvd = $this->recordProvider->getProvider()->findByName($name);
        $provider = $this->sender->getProvider();

        if ($provider instanceof ProviderInterface && $pvd) {
            $provider->applyOptions($pvd->getParameters());
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function acceptCallback($provider, array $response)
    {
        $provider = $this->using($provider)->sender->getProvider();

        if ($provider instanceof ProviderInterface) {
            $provider->processCallback($response);
            $provider->accept($this->recordProvider);
        }
    }

    /**
     * Allows to proxy method calls to the real SMS sender.
     *
     * @param $name
     * @param $arguments
     *
     * @return StorableSender|SmsSenderInterface
     */
    public function __call($name, $arguments)
    {
        if (is_callable(array($this->sender, $name))) {
            $result = call_user_func_array(array($this->sender, $name), $arguments);

            // don't break fluid interfaces
            return $result instanceof SmsSenderInterface ? $this : $result;
        }
    }

    /**
     * @return null|string
     */
    protected function getProviderClass()
    {
        return ($provider = $this->sender->getProvider()) ? get_class($provider) : null;
    }
}
