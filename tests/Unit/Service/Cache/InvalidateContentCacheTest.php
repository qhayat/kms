<?php

namespace Kms\Tests\Unit\Service\Cache;

use Kms\Core\Content\Entity\Content;
use Kms\Core\Content\Entity\Page;
use Kms\Core\Content\Entity\Post;
use Kms\Core\Content\Enum\Type;
use Kms\Core\Content\Repository\PageRepository;
use Kms\Core\Shared\Cache\Invalidator\ContentCacheInvalidator;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;
use Symfony\Component\Cache\Exception\InvalidArgumentException;

class InvalidateContentCacheTest extends TestCase
{
    private readonly TagAwareAdapterInterface $kmsCache;
    private readonly PageRepository $pageRepository;
    private readonly LoggerInterface $logger;

    public function setUp(): void
    {
        $this->kmsCache = $this->createMock(TagAwareAdapterInterface::class);
        $this->pageRepository = $this->createMock(PageRepository::class);
        $this->logger = $this->createMock(LoggerInterface::class);
    }

    public function testException(): void
    {
        $content = $this->createContent(Type::PAGE, 'my_page');
        $this->kmsCache
            ->expects($this->once())
            ->method('invalidateTags')
            ->with(['page_my_page'])
            ->willThrowException(new InvalidArgumentException());

        $this->logger
            ->expects($this->once())
            ->method('error');

        $invalidateContentCache = new ContentCacheInvalidator(
            $this->kmsCache,
            $this->pageRepository,
            $this->logger,
        );
        $invalidateContentCache->process($content);
    }

    public function testNotInvalidate(): void
    {
        $content = $this->createContent(Type::PAGE, 'my_page', 2);
        $this->kmsCache
            ->expects($this->once())
            ->method('invalidateTags')
            ->with(['page_my_page'])
            ->willReturn(false);

        $this->logger
            ->expects($this->once())
            ->method('error');

        $invalidateContentCache = new ContentCacheInvalidator(
            $this->kmsCache,
            $this->pageRepository,
            $this->logger,
        );
        $invalidateContentCache->process($content);
    }

    public function testPostContent(): void
    {
        $content = $this->createContent(Type::POST, 'my_post');
        $blogPage = $this->createMock(Page::class);
        $blogPage
            ->expects($this->once())
            ->method('getSlug')
            ->willReturn('blog');

        $this->pageRepository
            ->expects($this->once())
            ->method('findBlogPage')
            ->willReturn($blogPage);

        $recordedTags = [];
        $this->kmsCache
            ->method('invalidateTags')
            ->willReturnCallback(function (array $tags) use (&$recordedTags) {
                $recordedTags[] = $tags;

                return true;
            });

        $this->logger
            ->expects($this->never())
            ->method('error');

        $invalidateContentCache = new ContentCacheInvalidator(
            $this->kmsCache,
            $this->pageRepository,
            $this->logger,
        );
        $invalidateContentCache->process($content);

        $this->assertCount(2, $recordedTags);
        $this->assertEquals(['post_my_post'], $recordedTags[0]);
        $this->assertEquals(['page_blog'], $recordedTags[1]);
    }

    public function testPageContent(): void
    {
        $content = $this->createContent(Type::PAGE, 'my_page');
        $this->kmsCache
            ->expects($this->once())
            ->method('invalidateTags')
            ->with(['page_my_page'])
            ->willReturn(true);

        $this->logger
            ->expects($this->never())
            ->method('error');

        $invalidateContentCache = new ContentCacheInvalidator(
            $this->kmsCache,
            $this->pageRepository,
            $this->logger,
        );
        $invalidateContentCache->process($content);
    }

    private function createContent(Type $type, string $slug, int $slugExpectedCall = 1, int $typeExpectedCall = 1): Content
    {
        $contentFqcn = Page::class;
        if ($type->equals(Type::POST)) {
            $contentFqcn = Post::class;
        }

        $content = $this->createMock($contentFqcn);
        $content
            ->expects($this->exactly($typeExpectedCall))
            ->method('getType')
            ->willReturn($type);
        $content
            ->expects($this->exactly($slugExpectedCall))
            ->method('getSlug')
            ->willReturn($slug);

        return $content;
    }
}
