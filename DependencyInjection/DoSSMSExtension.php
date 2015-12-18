<?php

namespace DoS\SMSBundle\DependencyInjection;

use DoS\ResourceBundle\DependencyInjection\AbstractResourceExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class DoSSMSExtension extends AbstractResourceExtension
{
    /**
     * {@inheritdoc}
     */
    protected function getBundleConfiguration()
    {
        return new Configuration();
    }

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $config = parent::load($configs, $container);

        // fix the sms.http_adapter definition to point to the right adapter
        $container->setAlias('dos.sms.http_adapter', sprintf('dos.sms.http_adapter.%s', $config['http_adapter']));
        $container->getAlias('dos.sms.http_adapter')->setPublic(false);

        // define an alias to the real pooling service (will be used by the compiler pass)
        $container->setAlias('dos.sms.pool', sprintf('dos.sms.pool.%s', $config['pool']));

        $container->getDefinition('dos.sms.sender.delayed')->setArguments(array(
            new Reference('dos.sms.sender.storable'),
            new Reference('dos.sms.pool'),
        ));

        $container->setAlias('dos.sms.sender', $config['sender']);
        $container->setParameter('dos.sms.testing_number', $config['testing_number']);

        // set default provider
        $container->getDefinition('dos.sms.sender.default')->setArguments(array(
            new Reference('dos.sms.sender.provider.'.$config['provider']),
        ));

        $container->getDefinition('dos.sms.provider.provider')->addMethodCall('setDefaultProvider', array(
            $config['provider'],
        ));

        foreach ($config['providers'] as $name => $options) {
            $provider = $container->getDefinition('dos.sms.sender.provider.'.$name);
            $provider->addTag('dos.sms.provider', array('alias' => $name));

            $this->refactorArguments($container, $provider, (array) $options);

            // TODO: add compile pass (taged service) to allow to add none 'dos.sms.sender.provider.xx' pattern service.
            // to add others provider you just naming it with 'dos.sms.sender.provider.xxx' and then configurate
            // under ...
            // dos_sms:
            //     providers:
            //        xxx: ...
            $container->getDefinition('dos.sms.sender.default')
                ->addMethodCall('registerProvider', array(new Reference('dos.sms.sender.provider.'.$name)))
            ;
        }
    }

    private function refactorArguments(ContainerBuilder $container, Definition $definition, array $options)
    {
        $class = new \ReflectionClass($container->getParameter(str_replace('%', '', $definition->getClass())));

        if ($constructor = $class->getConstructor()) {
            $arguments = array();
            foreach ($constructor->getParameters() as $parameter) {
                if ($class = $parameter->getClass()) {
                    if ($class->getName() === 'SmsSender\HttpAdapter\HttpAdapterInterface') {
                        $arguments[] = new Reference('dos.sms.http_adapter');
                    }
                } elseif (array_key_exists($parameter->getName(), $options)) {
                    $arguments[] = $options[$parameter->getName()];
                }
            }

            $definition->setArguments($arguments);
        }
    }
}
