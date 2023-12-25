<?php

namespace Kms\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public const DEFAULT_MEDIA_UPLOAD_DIR = '%kernel.project_dir%/public/uploads/medias';
    public const DEFAULT_MEDIA_BASE_URL = '/uploads/medias';

    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('kms');

        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('media_upload_dir')
                    ->defaultValue(self::DEFAULT_MEDIA_UPLOAD_DIR)
                ->end()
                ->scalarNode('media_base_url')
                    ->defaultValue(self::DEFAULT_MEDIA_BASE_URL)
                ->end()
            ->end();

        return $treeBuilder;
    }
}
