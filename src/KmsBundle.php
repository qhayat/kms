<?php

namespace Kms;

use Kms\DependencyInjection\KmsExtension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class KmsBundle extends AbstractBundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new KmsExtension();
    }

    public function build(ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../config'));
        $loader->load('services.yaml');
        foreach (Finder::create()->files()->name('*.yaml')->in(__DIR__.'/../config/services') as $file) {
            $loader->load('services/'.$file->getFilename());
        }

        $container->setParameter('kms.root_dir', dirname(__DIR__));
        $loader->load('packages/cache.yaml');
        $loader->load('packages/twig.yaml');
        $loader->load('packages/doctrine_migrations.yaml');
        $loader->load('packages/doctrine.yaml');
        $loader->load('packages/nelmio_api_doc.yaml');
    }
}
