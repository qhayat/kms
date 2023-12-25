<?php

namespace Kms\Admin\Setting;

use Kms\Admin\Media\Builder\FinalUrlBuilder as MediaFinalUrlBuilder;
use Kms\Admin\Media\Repository\MediaRepository;
use Kms\Admin\Setting\Repository\SettingRepository;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;

class General
{
    public function __construct(
        private readonly SettingRepository $settingRepository,
        private readonly MediaRepository $mediaRepository,
        private readonly MediaFinalUrlBuilder $kmsMediaFinalUrlBuilder,
        protected readonly TagAwareAdapterInterface $kmsCache,
    ) {
    }

    public function get(string $key): ?string
    {
        $cacheKey = 'setting_'.$key;
        $cacheItem = $this->kmsCache->getItem($cacheKey);

        if (false === $cacheItem->isHit()) {
            $setting = $this->settingRepository->findOneBy(['key' => $key]);
            $cacheItem->set($setting?->getValue() ?? '');
            $cacheItem->tag(['setting', $cacheKey]);
            if ('site_logo' == $key) {
                $cacheItem->set($this->getLogoFinalUrl($cacheItem->get()));
            }

            $this->kmsCache->save($cacheItem);
        }

        return $cacheItem->get();
    }

    public function getAll(): array
    {
        $cacheKey = 'setting_all';
        $cacheItem = $this->kmsCache->getItem($cacheKey);

        if (false === $cacheItem->isHit()) {
            $settings = $this->settingRepository->toArray();
            if (isset($settings['site_logo'])) {
                $settings['site_logo'] = $this->getLogoFinalUrl($settings['site_logo']);
            }

            $cacheItem->set($settings);
            $cacheItem->tag(['setting', $cacheKey]);
            $this->kmsCache->save($cacheItem);
        }

        return $cacheItem->get();
    }

    public function getLogoFinalUrl(string $mediaId): string
    {
        $media = $this->mediaRepository->find($mediaId);
        if (null === $media) {
            return '';
        }

        return $this->kmsMediaFinalUrlBuilder->build($media);
    }
}
