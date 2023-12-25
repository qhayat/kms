<?php

namespace Kms\Core\Content\ObjectView;

use Kms\Admin\Media\Builder\FinalUrlBuilder;
use Kms\Core\Content\Entity\Content as ContentEntity;
use Kms\Core\Content\Entity\Page;
use Kms\Core\ObjectView\BuilderInterface;
use Kms\Core\Shared\Helper\StringDateBuilder;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ContentBuilder implements BuilderInterface
{
    public function __construct(
        private readonly UrlGeneratorInterface $urlGenerator,
        private readonly FinalUrlBuilder $kmsMediaFinalUrlBuilder,
    ) {
    }

    public function build(string $objectViewFqcn, object $entity = null, array $data = []): Content
    {
        if (!$entity instanceof ContentEntity) {
            throw new \InvalidArgumentException(sprintf('The entity must be an instance of "%s"', Content::class));
        }

        $routeName = sprintf('kms_%s', $entity instanceof Page ? 'page' : 'post');
        $featuredImageUrl = null;
        if (null !== $entity->getFeaturedImage() && null !== $entity->getFeaturedImage()->getPath()) {
            $featuredImageUrl = $this->kmsMediaFinalUrlBuilder->build($entity->getFeaturedImage());
        }

        return new Content(
            title: $entity->getTitle() ?? '',
            content: $entity->getContent() ?? '',
            type: $entity->getType()->value,
            createdAt: $entity->getCreatedAt(),
            createdAtString: (new StringDateBuilder($entity->getCreatedAt()))
                ->shortWeekDayName()
                ->shortMonthName()
                ->withTime()
                ->build(),
            url: $this->urlGenerator->generate($routeName, ['slug' => $entity->getSlug()], UrlGeneratorInterface::ABSOLUTE_URL),
            author: new Author(
                fullName: $entity->getAuthor()?->fullName() ?? '',
                role: $entity->getAuthor()?->getRoles()[0] ?? '',
            ),
            excerpt: $entity->getExcerpt() ?? '',
            featuredImageUrl: $featuredImageUrl,
            relateds: $data['relateds'] ?? null,
            extra: $this->extra($entity),
        );
    }

    private function extra(object $entity): array
    {
        $data = [];
        if ($entity instanceof Page) {
            $data['isHomePage'] = $entity->isHomePage();
            $data['isBlogPage'] = $entity->isBlogPage();
        }

        return $data;
    }

    public function supports(string $objectViewFqcn): bool
    {
        return Content::class === $objectViewFqcn;
    }
}
