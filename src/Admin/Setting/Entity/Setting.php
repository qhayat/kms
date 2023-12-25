<?php

namespace Kms\Admin\Setting\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Kms\Admin\Media\Repository\MediaRepository;
use Kms\Core\Shared\Entity\Trait\Author as AuthorTrait;
use Kms\Core\Shared\Entity\Trait\Guid as GuidTrait;
use Kms\Core\Shared\Entity\Trait\Timestampable as TimestampableTrait;

#[HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: MediaRepository::class)]
class Setting
{
    use GuidTrait;
    use TimestampableTrait;
    use AuthorTrait;

    #[ORM\Column(length: 255)]
    private ?string $key = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $value = null;

    public function getKey(): ?string
    {
        return $this->key;
    }

    public function setKey(string $key): static
    {
        $this->key = $key;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): static
    {
        $this->value = $value;

        return $this;
    }
}
