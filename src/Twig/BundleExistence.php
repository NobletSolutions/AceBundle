<?php

namespace NS\AceBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class BundleExistence extends AbstractExtension
{
    private array $kernelBundles;

    public function __construct(array $kernelBundles)
    {
        $this->kernelBundles = $kernelBundles;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('bundleExists', [$this, 'bundleExists']),
        ];
    }

    public function bundleExists(string $bundle): bool
    {
        return array_key_exists($bundle, $this->kernelBundles);
    }
}
