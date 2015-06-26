<?php

namespace DoS\SMSBundle\DependencyInjection;

use DoS\ResourceBundle\DependencyInjection\AbstractResourceConfiguration;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class Configuration extends AbstractResourceConfiguration
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('dos_sms');

        $this->setDefaults($rootNode, array(
            'classes' => array(
                'sms_provider' => array(
                    'model' => 'DoS\SMSBundle\Model\Provider',
                    'controller' => 'DoS\SMSBundle\Controller\ProviderController',
                    'interface' => 'DoS\SMSBundle\Model\ProviderInterface',
                    'form' => array(
                        'default' => 'DoS\SMSBundle\Form\Type\ProviderType',
                    ),
                ),
                'sms_record' => array(
                    'model' => 'DoS\SMSBundle\Model\Record',
                    'controller' => 'DoS\SMSBundle\Controller\RecordController',
                    'interface' => 'DoS\SMSBundle\Model\RecordInterface',
                    'form' => array(),
                ),
            ),
            'validation_groups' => array(
                'sms_provider' => array(),
                'sms_record' => array(),
            ),
        ));

        $this->addHttpAdapterNode($rootNode);
        $this->addPoolNode($rootNode);
        $this->addProvidersNode($rootNode);
        $this->addDefaultSender($rootNode);

        return $treeBuilder;
    }

    protected function addProvidersNode(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->scalarNode('provider')
                    ->defaultValue('dummy')
                ->end()
                ->scalarNode('testing_number')
                    ->defaultNull()->cannotBeEmpty()
                ->end()
                ->arrayNode('providers')
                    ->isRequired()
                    ->requiresAtLeastOneElement()
                    ->useAttributeAsKey('name')
                    ->prototype('variable')
                ->end()
            ->end();

        return $rootNode;
    }

    protected function addHttpAdapterNode(ArrayNodeDefinition $rootNode)
    {
        $supportedHttpAdapters = array('curl', 'buzz');

        $rootNode
            ->children()
                ->scalarNode('http_adapter')
                ->defaultValue('curl')
                ->validate()
                    ->ifNotInArray($supportedHttpAdapters)
                    ->thenInvalid('The http_adapter %s is not supported. Please choose one of '.implode(', ', $supportedHttpAdapters))
                ->end()
            ->end();

        return $rootNode;
    }

    protected function addPoolNode(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->scalarNode('pool')
                ->defaultValue('memory')
                ->cannotBeEmpty()
            ->end();

        return $rootNode;
    }

    protected function addDefaultSender(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->scalarNode('sender')
                ->defaultValue('dos.sms.sender.delayed')
                ->cannotBeEmpty()
            ->end();

        return $rootNode;
    }
}
