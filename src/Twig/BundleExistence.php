<?php

namespace NS\AceBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class BundleExistence extends AbstractExtension
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('bundleExists', [$this, 'bundleExists']),
        ];
    }

    /**
     *
     * @param string $bundle
     * @return boolean
     */
    public function bundleExists($bundle)
    {
        return array_key_exists($bundle, $this->container->getParameter('kernel.bundles'));
    }

    /**
     *
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ns_ace_bundle_existence';
    }
}
