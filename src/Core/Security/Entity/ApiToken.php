<?php

namespace Kms\Core\Security\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Kms\Core\Security\Repository\ApiTokenRepository;
use Kms\Core\Secutiry\Entity\Permission;
use Kms\Core\Shared\Entity\Trait\Guid;
use Kms\Core\Shared\Entity\Trait\Timestampable;

#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: ApiTokenRepository::class)]
class ApiToken
{
    use Guid;
    use Timestampable;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $token = null;

    #[ORM\Column(type: Types::BOOLEAN)]
    private ?bool $enabled = false;

    #[ORM\ManyToMany(targetEntity: Permission::class, inversedBy: 'apiTokens')]
    private Collection $permissions;

    public function __construct()
    {
        $this->permissions = new ArrayCollection();
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

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): static
    {
        $this->token = $token;

        return $this;
    }

    public function getEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): static
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getPermissions(): Collection
    {
        return $this->permissions;
    }

    public function setPermissions(Collection $permissions): static
    {
        $this->permissions = $permissions;

        return $this;
    }

    public function addPermission(Permission $permission): static
    {
        $this->permissions->add($permission);

        return $this;
    }

    public function removePermission(Permission $permission): static
    {
        $this->permissions->removeElement($permission);

        return $this;
    }

    public function hasPermission(string $key): bool
    {
        /** @var Permission $permission */
        foreach ($this->permissions as $permission) {
            if ($permission->getKey() === $key) {
                return true;
            }
        }

        return false;
    }
}
