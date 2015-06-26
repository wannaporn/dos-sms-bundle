<?php

namespace DoS\SMSBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ProviderCallbackPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $providerIds = $container->findTaggedServiceIds('dos.sms.provider');
        $callbackIds = $container->findTaggedServiceIds('dos.sms.provider_callback');

        foreach ($providerIds as $id => $tags) {
            $class = $container->getDefinition($id)->getClass();
            $class = $container->getParameter(str_replace('%', '', $class));

            if (!(new \ReflectionClass($class))->implementsInterface('DoS\SMSBundle\SMS\ProviderInterface')) {
                continue;
            }

            foreach ($callbackIds as $name => $attrs) {
                if ($tags[0]['alias'] == $attrs[0]['alias']) {
                    $container->getDefinition($id)->addMethodCall('setCallback', array(new Reference($name)));
                }
            }
        }
    }
}
