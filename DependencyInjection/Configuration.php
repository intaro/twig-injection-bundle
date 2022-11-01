<?php

namespace Intaro\TwigInjectionBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('intaro_twig_injection');
        if (!\method_exists($treeBuilder, 'getRootNode')) {
            // BC layer for symfony/config 4.1 and older
            $rootNode = $treeBuilder->root('intaro_twig_injection');
        }

        return $treeBuilder;
    }
}
