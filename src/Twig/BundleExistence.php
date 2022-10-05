<?php

namespace NS\AceBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class BundleExistence extends AbstractExtension
{
    protected ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('bundleExists', [$this, 'bundleExists']),
        ];
    }

    public function bundleExists(string $bundle): bool
    {
        return array_key_exists($bundle, $this->container->getParameter('kernel.bundles'));
    }
}
