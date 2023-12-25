<?php

namespace Kms\Core\Content\UseCase;

use App\DTO\CollectionResponse;
use Kms\Core\Content\Entity\Content as ContentEntity;
use Kms\Core\Content\Entity\Page;
use Kms\Core\Content\Entity\Post;
use Kms\Core\Content\Enum\Status;
use Kms\Core\Content\Enum\Type;
use Kms\Core\Content\ObjectView\Content;
use Kms\Core\Content\Repository\ContentRepository;
use Kms\Core\Content\Repository\ContentRepositoryInterface;
use Kms\Core\ObjectView\Builder as ObjectViewBuilder;

class GetContentBySlugAndType
{
    public function __construct(
        private readonly ObjectViewBuilder $objectViewBuilder,
        private readonly ContentRepositoryInterface $pageRepository,
        private readonly ContentRepositoryInterface $postRepository,
        private readonly ContentRepository $contentRepository,
    ) {
    }

    public function get(Type $type, ?string $slug, array $options = []): ?Content
    {
        $data = [];
        $content = null === $slug
            ? $this->pageRepository->findHomePage()
            : $this->contentRepository->findOneBySlug($slug);

        if ($type->equals(Type::PAGE) && $content instanceof Post) {
            $content = null;
        }

        if ($type->equals(Type::POST) && $content instanceof Page) {
            $content = null;
        }

        if (null === $content) {
            return null;
        }

        if ($this->haveToFetchPosts($content)) {
            $page = $options['page']['number'] ?? 1;
            $maxResults = $options['page']['size'] ?? 10;
            $posts = $this->postRepository->paginate(
                $page,
                $maxResults,
                array_merge(['createdAt' => 'DESC'], $options['sorts'] ?? []),
                array_merge(['status' => Status::PUBLISHED], $options['filters'] ?? [])
            );

            $data['relateds'] = new CollectionResponse(
                array_map(fn (Post $post) => $this->objectViewBuilder->build(Content::class, $post), $posts),
                $this->postRepository->total($options['filters'] ?? []),
                $page,
                $maxResults,
            );
        }

        if (!isset($data['relateds']) || empty($data['relateds'])) {
            $data['relateds'] = array_map(fn (ContentEntity $content) => $this->objectViewBuilder->build(Content::class, $content), $content->getRelateds()->toArray());
        }

        /**
         * @var Content $contentView
         */
        $contentView = $this->objectViewBuilder->build(Content::class, $content, $data);

        return $contentView;
    }

    private function haveToFetchPosts(ContentEntity $content): bool
    {
        return $content instanceof Page && $content->isBlogPage();
    }
}
