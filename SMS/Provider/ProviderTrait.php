<?php

namespace DoS\SMSBundle\SMS\Provider;

use DoS\SMSBundle\Provider\RecordProvider;
use DoS\SMSBundle\SMS\ProviderCallbackInterface;
use DoS\SMSBundle\SMS\ProviderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;

trait ProviderTrait
{
    /**
     * @var ProviderCallbackInterface
     */
    protected $callback;

    public function setCallback(ProviderCallbackInterface $callback = null)
    {
        $this->callback = $callback;
    }

    public function processCallback(array $data)
    {
        if ($this->callback) {
            $this->callback->process($data);
        }
    }

    public function getCallbackResults()
    {
        if ($this->callback) {
            return $this->callback->getResults();
        }

        return array();
    }

    public function accept(RecordProvider $visitor)
    {
        /* @var ProviderInterface $this */
        $visitor->visit($this);
    }

    public function applyOptions(array $options = array())
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        $this->validateOptions($options);

        foreach ($options as $key => $value) {
            $accessor->setValue($this, $key, $value);
        }
    }

    public function validateOptions(array $options)
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $resolver->resolve($options);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    }
}
