<?php

namespace Kms\Admin\Content\Service\Page;

use Doctrine\ORM\EntityManagerInterface;
use Kms\Core\Content\Entity\Page;
use Kms\Core\Content\Repository\ContentRepositoryInterface;
use Kms\Core\Shared\Cache\Invalidator\CacheInvalidatorInterface;

class BlogPageSetter
{
    public function __construct(
        private readonly ContentRepositoryInterface $pageRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly CacheInvalidatorInterface $contentCacheInvalidator,
    ) {
    }

    public function set(int|Page $page): void
    {
        if (is_int($page)) {
            $page = $this->pageRepository->find($page);
        }

        if (null == $page) {
            return;
        }

        foreach ($this->pageRepository->findBy(['blogPage' => true]) as $blogPage) {
            $blogPage->setIsBlogPage(false);
            $this->entityManager->persist($blogPage);
        }

        $page->setIsBlogPage(true);
        $this->entityManager->persist($page);
        $this->entityManager->flush();

        $this->contentCacheInvalidator->process($page);
    }
}
