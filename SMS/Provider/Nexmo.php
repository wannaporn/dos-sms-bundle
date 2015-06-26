<?php

namespace DoS\SMSBundle\SMS\Provider;

use DoS\SMSBundle\SMS\ProviderInterface;
use SmsSender\HttpAdapter\HttpAdapterInterface;
use SmsSender\Provider\NexmoProvider;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Nexmo extends NexmoProvider implements ProviderInterface
{
    use ProviderTrait;

    public function __construct(HttpAdapterInterface $adapter, $api_key, $api_secret, $international_prefix = '+66')
    {
        $this->validateOptions(array(
            'api_key' => $api_key,
            'api_secret' => $api_secret,
            'international_prefix' => $international_prefix,
        ));

        parent::__construct($adapter, $api_key, $api_secret, $international_prefix);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'api_key' => null,
            'api_secret' => null,
            'international_prefix' => '+66',
        ));

        $resolver->setRequired(array(
            'api_key',
            'api_secret',
        ));
    }
}
