<?php

namespace Kms\Admin\Content\Factory;

use Kms\Core\Content\Entity\Page;
use Kms\Core\Content\Enum\Status;

class DefaultHomePage
{
    public function create(): Page
    {
        return (new Page())
            ->setTitle('KMS')
            ->setContent('A cms like no other')
            ->setStatus(Status::PUBLISHED)
            ->setIsHomePage(true)
            ->setIsBlogPage(false)
            ->setSlug('home');
    }
}
