<?php

namespace Kms\Core\Secutiry\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Kms\Core\Security\Entity\ApiToken;
use Kms\Core\Security\Repository\PermissionRepository;
use Kms\Core\Shared\Entity\Trait\Guid;
use Kms\Core\Shared\Entity\Trait\Timestampable;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: PermissionRepository::class)]
class Permission
{
    use Guid;
    use Timestampable;

    #[ORM\Column(length: 255)]
    private ?string $key = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToMany(targetEntity: ApiToken::class, mappedBy: 'permissions')]
    private Collection $apiTokens;

    public function __construct()
    {
        $this->apiTokens = new ArrayCollection();
    }

    public function getKey(): ?string
    {
        return $this->key;
    }

    public function setKey(string $key): static
    {
        $this->key = $key;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getApiTokens(): Collection
    {
        return $this->apiTokens;
    }

    public function setApiTokens(Collection $apiTokens): static
    {
        $this->apiTokens = $apiTokens;

        return $this;
    }

    public function addApiToken(ApiToken $apiToken): static
    {
        if (!$this->apiTokens->contains($apiToken)) {
            $this->apiTokens->add($apiToken);
        }

        return $this;
    }

    public function removeApiToken(ApiToken $apiToken): static
    {
        if ($this->apiTokens->contains($apiToken)) {
            $this->apiTokens->removeElement($apiToken);
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->key ?? '';
    }
}
