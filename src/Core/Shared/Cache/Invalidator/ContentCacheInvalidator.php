<?php

namespace Kms\Core\Shared\Cache\Invalidator;

use Kms\Core\Content\Entity\Content;
use Kms\Core\Content\Entity\Page;
use Kms\Core\Content\Entity\Post;
use Kms\Core\Content\Repository\PageRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;
use Symfony\Component\Cache\Exception\InvalidArgumentException;

class ContentCacheInvalidator implements CacheInvalidatorInterface
{
    public function __construct(
        private readonly TagAwareAdapterInterface $kmsCache,
        private readonly PageRepository $pageRepository,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function process(object $entity): void
    {
        if (!$entity instanceof Content) {
            return;
        }

        try {
            $content = $entity;
            $slug = $content instanceof Page && $content->isHomePage() ? 'home' : $content->getSlug();
            if (!$this->kmsCache->invalidateTags([sprintf('%s_%s', $content->getType()->value, $slug)])) {
                $this->logger->error(sprintf('Cache invalidation failed for content %s', $slug));
            }

            if ($content instanceof Post && null !== $blogPage = $this->pageRepository->findBlogPage()) {
                if (!$this->kmsCache->invalidateTags([sprintf('page_%s', $blogPage->getSlug())])) {
                    $this->logger->error(sprintf('Cache invalidation failed for blog page %s', $blogPage->getSlug()));
                }
            }
        } catch (InvalidArgumentException $e) {
            $this->logger->error($e, [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
        }
    }
}
