<?php

namespace NS\AceBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 20/12/16
 * Time: 4:51 PM
 */
class TwigFormThemeCompilerPass implements CompilerPassInterface
{
    /**
     * @inheritDoc
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->hasParameter('twig.form.resources')) {
            $resources = $container->getParameter('twig.form.resources');

            if (!in_array('NSAceBundle:Form:fields.html.twig', $resources) && !in_array('@NSAce/Form/fields.html.twig', $resources)) {
                $resources[] = '@NSAce/Form/fields.html.twig';

                $container->setParameter('twig.form.resources', $resources);
            }
        }
    }
}
