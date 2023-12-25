<?php

namespace Kms\Admin\Media\Builder;

use Kms\Admin\Media\Entity\Media;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemAdapter;
use League\Flysystem\UnableToGenerateTemporaryUrl;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class FinalUrlBuilder
{
    public function __construct(
        private readonly FilesystemAdapter $adapter,
        private readonly LoggerInterface $logger,
        private readonly ParameterBagInterface $parameter,
        private ?string $kmsMediaBaseUrl = null,
    ) {
    }

    public function build(Media $media): string
    {
        if (null === $media->getPath()) {
            return '';
        }

        $url = '';
        $filesystem = new Filesystem($this->adapter, [
            'public_url' => $this->determineBaseUrl(),
        ]);

        try {
            return $filesystem->temporaryUrl($media->getPath(), new \DateTime('+1 hour'));
        } catch (UnableToGenerateTemporaryUrl) {
            $this->logger->info(sprintf('Unable to generate temporary URL for media %s', $media->getId()));
        }

        try {
            $url = $filesystem->publicUrl($media->getPath());
        } catch (\Throwable $e) {
            $this->logger->error(sprintf('Unable to generate URL for media %s', $media->getId()));
        }

        return $url;
    }

    public function determineBaseUrl(): string
    {
        if (null !== $this->kmsMediaBaseUrl) {
            return $this->kmsMediaBaseUrl;
        }

        return $this->parameter->get('kms.media_base_url') ?? '';
    }
}
