<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 21/02/17
 * Time: 10:42 AM
 */

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

            if ($container->hasParameter('knp_menu.renderer.twig.template') && in_array($container->getParameter('knp_menu.renderer.twig.template'),['KnpMenuBundle::menu.html.twig','@KnpMenu/menu.html.twig'])) {
                $container->setParameter('knp_menu.renderer.twig.template', '@NSAce/Menu/menu.html.twig');
            }
        }

        if ($container->hasParameter('knp_paginator.template.pagination') && in_array($container->getParameter('knp_paginator.template.pagination'),['KnpPaginatorBundle:Pagination:sliding.html.twig','@KnpPaginator/Pagination/sliding.html.twig'])) {
            $container->setParameter('knp_paginator.template.pagination', '@NSAce/Form/pagination.html.twig');
        }
    }
}
