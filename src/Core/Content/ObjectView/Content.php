<?php

namespace Kms\Core\Content\ObjectView;

use App\DTO\CollectionResponse;

class Content
{
    /**
     * @param string $title
     * @param string $content
     * @param string $type
     * @param \DateTimeInterface $createdAt
     * @param string $createdAtString
     * @param string $url
     * @param Author $author
     * @param string|null $excerpt
     * @param string|null $featuredImageUrl
     * @param CollectionResponse|array|null $relateds
     * @param bool $isHomePage
     * @param bool $isBlogPage
     */
    public function __construct(
        public readonly string $title,
        public readonly string $content,
        public readonly string $type,
        public readonly \DateTimeInterface $createdAt,
        public readonly string $createdAtString,
        public readonly string $url,
        public readonly Author $author,
        public readonly ?string $excerpt = null,
        public readonly ?string $featuredImageUrl = null,
        public readonly CollectionResponse|array|null $relateds = null,
        public readonly array $extra = [],
    ) {
    }
}
