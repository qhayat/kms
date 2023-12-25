<?php

namespace Kms\Core\Content\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Kms\Core\Content\Repository\PageRepository;

#[HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: PageRepository::class)]
class Page extends Content
{
    #[ORM\Column(nullable: true)]
    private ?bool $homePage = null;

    #[ORM\Column(nullable: true)]
    private ?bool $blogPage = null;

    public function isHomePage(): ?bool
    {
        return $this->homePage;
    }

    public function setIsHomePage(?bool $homePage): static
    {
        $this->homePage = $homePage;

        return $this;
    }

    public function isBlogPage(): ?bool
    {
        return $this->blogPage;
    }

    public function setIsBlogPage(?bool $blogPage): static
    {
        $this->blogPage = $blogPage;

        return $this;
    }
}
