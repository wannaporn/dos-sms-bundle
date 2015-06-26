<?php

namespace DoS\SMSBundle;

use DoS\ResourceBundle\DependencyInjection\AbstractResourceBundle;
use DoS\SMSBundle\DependencyInjection\Compiler;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DoSSMSBundle extends AbstractResourceBundle
{
    public function build(ContainerBuilder $builder)
    {
        parent::build($builder);

        $builder->addCompilerPass(new Compiler\ProviderCallbackPass());
    }
}
