<?php

namespace NS\AceBundle;

use NS\AceBundle\DependencyInjection\Compiler\KnpCompilerPass;
use NS\AceBundle\DependencyInjection\Compiler\TwigFormThemeCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class NSAceBundle extends Bundle
{
    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new TwigFormThemeCompilerPass());
        $container->addCompilerPass(new KnpCompilerPass());
    }
}
