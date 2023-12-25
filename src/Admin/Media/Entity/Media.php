<?php

namespace Kms\Admin\Media\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Kms\Admin\Media\Repository\MediaRepository;
use Kms\Core\Shared\Entity\Trait\Author as AuthorTrait;
use Kms\Core\Shared\Entity\Trait\Guid as GuidTrait;
use Kms\Core\Shared\Entity\Trait\Timestampable as TimestampableTrait;

#[HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: MediaRepository::class)]
class Media
{
    use GuidTrait;
    use TimestampableTrait;
    use AuthorTrait;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $path = null;

    #[ORM\Column(length: 255)]
    private ?string $extension = null;

    private ?string $image = null;

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): static
    {
        $this->path = $path;

        return $this;
    }

    public function getExtension(): ?string
    {
        return $this->extension;
    }

    public function setExtension(string $extension): static
    {
        $this->extension = $extension;

        return $this;
    }
}
