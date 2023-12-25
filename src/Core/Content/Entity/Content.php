<?php

namespace Kms\Core\Content\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Kms\Admin\Media\Entity\Media;
use Kms\Core\Content\Enum\Status;
use Kms\Core\Content\Enum\Type;
use Kms\Core\Content\Repository\ContentRepository;
use Kms\Core\Shared\Entity\Trait\Author as AuthorTrait;
use Kms\Core\Shared\Entity\Trait\Guid as GuidTrait;
use Kms\Core\Shared\Entity\Trait\Timestampable as TimestampableTrait;

#[ORM\Entity(repositoryClass: ContentRepository::class)]
#[ORM\DiscriminatorColumn(name: 'type', type: Types::STRING)]
#[ORM\InheritanceType('JOINED')]
#[ORM\DiscriminatorMap([
    'post' => Post::class,
    'page' => Page::class,
])]
class Content
{
    use GuidTrait;
    use TimestampableTrait;
    use AuthorTrait;

    #[ORM\Column(length: 255)]
    protected ?string $title = null;

    #[ORM\Column(length: 255, unique: true)]
    protected ?string $slug = null;

    #[ORM\Column(type: Types::TEXT)]
    protected ?string $content = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    protected ?string $excerpt = null;

    #[ORM\Column(length: 255)]
    protected ?Status $status = null;

    #[ORM\ManyToOne(cascade: ['persist', 'merge'])]
    protected ?Media $featuredImage = null;

    #[ORM\Column(length: 255, nullable: true)]
    protected ?string $image = null;

    #[ORM\ManyToMany(targetEntity: self::class)]
    private Collection $relateds;

    public function __construct()
    {
        $this->relateds = new ArrayCollection();
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): void
    {
        $this->image = $image;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getExcerpt(): ?string
    {
        return $this->excerpt;
    }

    public function setExcerpt(string $excerpt): static
    {
        $this->excerpt = $excerpt;

        return $this;
    }

    public function getStatus(): ?Status
    {
        return $this->status;
    }

    public function setStatus(Status $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getType(): Type
    {
        if ($this instanceof Post) {
            return Type::POST;
        }

        return Type::PAGE;
    }

    public function getFeaturedImage(): ?Media
    {
        return $this->featuredImage ?? new Media();
    }

    public function setFeaturedImage(?Media $featuredImage): static
    {
        $this->featuredImage = $featuredImage;

        $this->image = null;

        return $this;
    }

    public function imageForIndex(): ?string
    {
        return $this->featuredImage?->getPath();
    }

    /**
     * @return Collection<int, self>
     */
    public function getRelateds(): Collection
    {
        return $this->relateds;
    }

    public function addRelated(self $related): static
    {
        if (!$this->relateds->contains($related)) {
            $this->relateds->add($related);
        }

        return $this;
    }

    public function removeRelated(self $related): static
    {
        $this->relateds->removeElement($related);

        return $this;
    }

    public function __toString(): string
    {
        $className = explode('\\', static::class);
        $className = end($className);
        return sprintf('%s (%s)', $this->title ?? '', $className);
    }
}
