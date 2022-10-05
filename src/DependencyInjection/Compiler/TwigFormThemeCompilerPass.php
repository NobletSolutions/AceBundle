<?php declare(strict_types=1);

namespace NS\AceBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TwigFormThemeCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if ($container->hasParameter('twig.form.resources')) {
            $resources = (array)$container->getParameter('twig.form.resources');

            if (!in_array('NSAceBundle:Form:fields.html.twig', $resources) && !in_array('@NSAce/Form/fields.html.twig', $resources)) {
                $resources[] = '@NSAce/Form/fields.html.twig';

                $container->setParameter('twig.form.resources', $resources);
            }
        }
    }
}
