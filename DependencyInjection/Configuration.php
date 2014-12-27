<?php

namespace Asapo\Bundle\Sulu\FormBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * {@inheritdoc}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('asapo_sulu_form');

        $rootNode
            ->children()
                ->arrayNode('forms')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('type')->end()
                            ->scalarNode('entity')->end()
                            ->scalarNode('form_type')->end()
                            ->arrayNode('templates')
                                ->addDefaultsIfNotSet()
                                ->children()
                                    ->scalarNode('form')->defaultValue('AsapoSuluFormBundle:Form:form.html.twig')->end()
                                    ->scalarNode('success')->defaultValue('AsapoSuluFormBundle:Form:success.html.twig')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
