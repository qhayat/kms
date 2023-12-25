<?php

namespace Kms\Admin\Media\Factory;

use Kms\Admin\Media\Entity\Media;
use Symfony\Bundle\SecurityBundle\Security;

class CreateFromString
{
    public function __construct(private readonly Security $security)
    {
    }

    public function create(string $image): Media
    {
        $extension = pathinfo($image, PATHINFO_EXTENSION);
        $name = pathinfo($image, PATHINFO_FILENAME);

        return (new Media())
            ->setName($name)
            ->setPath($image)
            ->setExtension($extension)
            ->setAuthor($this->security->getUser());
    }
}
