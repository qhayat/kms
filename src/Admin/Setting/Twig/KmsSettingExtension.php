<?php

namespace Kms\Admin\Setting\Twig;

use Kms\Admin\Setting\General as GeneralSetting;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class KmsSettingExtension extends AbstractExtension
{
    public function __construct(private readonly GeneralSetting $generalSetting)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('kms_setting', [$this, 'kmsPostsByCategory']),
        ];
    }

    public function kmsPostsByCategory(string $key): mixed
    {
        return $this->generalSetting->get($key);
    }
}
