<?php

namespace DoS\SMSBundle\Form\Type;

use DoS\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\FormBuilderInterface;

class ProviderType extends AbstractResourceType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array())
            ->add('description', 'text', array())
            ->add('price', 'dos_money', array())
            ->add('parameters', 'dos_yaml', array())
            ->add('actived', 'checkbox', array(
                'required' => false,
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'dos_sms_provider';
    }
}
