<?php

namespace Kms\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

class KmsExtension extends Extension
{
    public function getAlias(): string
    {
        return 'kms';
    }

    public function load(array $configs, ContainerBuilder $container)
    {
        $config = $this->processConfiguration(new Configuration(), $configs);
        foreach ($config as $key => $value) {
            $container->setParameter('kms.'.$key, $value);
        }
    }
}
