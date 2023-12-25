<?php

namespace Kms\Website;

use Kms\Admin\Setting\General as GeneralSetting;

class Kms
{
    public readonly ?string $site_title;
    public readonly ?string $site_description;
    public readonly ?string $site_logo;

    public function __construct(GeneralSetting $settingGeneral)
    {
        $allSettings = $settingGeneral->getAll();
        $this->site_title = $allSettings['site_name'] ?? 'KMS';
        $this->site_description = $allSettings['site_description'] ?? null;
        $this->site_logo = $allSettings['site_logo'] ?? null;
    }
}
