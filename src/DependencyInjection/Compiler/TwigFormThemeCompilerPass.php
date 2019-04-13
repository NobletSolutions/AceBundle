<?php

namespace NS\AceBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TwigFormThemeCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if ($container->hasParameter('twig.form.resources')) {
            $resources = $container->getParameter('twig.form.resources');

            if (!in_array('NSAceBundle:Form:fields.html.twig', $resources, true)) {
                $resources[] = 'NSAceBundle:Form:fields.html.twig';

                $container->setParameter('twig.form.resources', $resources);
            }
        }
    }
}
