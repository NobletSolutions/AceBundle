<?php declare(strict_types=1);

namespace NS\AceBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('ns_ace');

        $treeBuilder
            ->getRootNode()
                ->children()
                    ->scalarNode('use_knp_menu')->defaultTrue()->end()
                ->end();

        return $treeBuilder;
    }
}
