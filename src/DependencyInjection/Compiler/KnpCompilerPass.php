<?php

namespace NS\AceBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class KnpCompilerPass implements CompilerPassInterface
{
    /**
     * @inheritDoc
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->getParameter('ns_ace.use_knp_menu')) {
            if ($container->hasParameter('knp_menu.renderer.twig.options')) {
                $params = $container->getParameter('knp_menu.renderer.twig.options');
                $params['currentClass'] = 'active';
                $params['ancestorClass'] = 'active open';
                $container->setParameter('knp_menu.renderer.twig.options', $params);
            }

            if ($container->hasParameter('knp_menu.renderer.twig.template') && $container->getParameter('knp_menu.renderer.twig.template') === 'KnpMenuBundle::menu.html.twig') {
                $container->setParameter('knp_menu.renderer.twig.template', 'NSAceBundle:Menu:menu.html.twig');
            }
        }

        if ($container->hasParameter('knp_paginator.template.pagination') && $container->getParameter('knp_paginator.template.pagination') === 'KnpPaginatorBundle:Pagination:sliding.html.twig') {
            $container->setParameter('knp_paginator.template.pagination', 'NSAceBundle:Form:pagination.html.twig');
        }
    }
}
