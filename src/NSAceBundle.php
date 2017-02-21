<?php

namespace NS\AceBundle;

use NS\AceBundle\DependencyInjection\Compiler\KnpCompilerPass;
use NS\AceBundle\DependencyInjection\Compiler\TwigFormThemeCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class NSAceBundle extends Bundle
{
    /**
     * @inheritDoc
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new TwigFormThemeCompilerPass());
        $container->addCompilerPass(new KnpCompilerPass());
    }
}
