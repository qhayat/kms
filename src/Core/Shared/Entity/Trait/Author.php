<?php

namespace Kms\Core\Shared\Entity\Trait;

use Doctrine\ORM\Mapping as ORM;
use Kms\Admin\User\Entity\User;

trait Author
{
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    protected ?User $author = null;

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(User $author): static
    {
        $this->author = $author;

        return $this;
    }
}
