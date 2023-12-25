<?php

namespace Kms\Admin\Content\Twig;

use Kms\Core\Content\Entity\Post;
use Kms\Core\Content\ObjectView\Content;
use Kms\Core\Content\Repository\PostRepository;
use Kms\Core\ObjectView\Builder as ObjectViewBuilder;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class KmsPostExtension extends AbstractExtension
{
    public function __construct(
        private readonly PostRepository $postRepository,
        private readonly ObjectViewBuilder $objectViewBuilder,
    ) {
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('kms_posts_by_category', [$this, 'kmsPostsByCategory']),
            new TwigFunction('kms_posts_by_categories', [$this, 'kmsPostsByCategories']),
        ];
    }

    /**
     * @return array<int, Content>
     */
    public function kmsPostsByCategory(string $categoryName): array
    {
        return array_map(
            fn (Post $post) => $this->objectViewBuilder->build(Content::class, $post),
            $this->postRepository->byCategory($categoryName)
        );
    }

    /**
     * @param array<int, string> $categoriesName
     *
     * @return array<int, Content>
     */
    public function kmsPostsByCategories(array $categoriesName): array
    {
        return array_map(
            fn (Post $post) => $this->objectViewBuilder->build(Content::class, $post),
            $this->postRepository->byCategories($categoriesName)
        );
    }
}
