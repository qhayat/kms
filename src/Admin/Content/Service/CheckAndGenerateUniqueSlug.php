<?php

namespace Kms\Admin\Content\Service;

use Kms\Core\Content\Entity\Content;
use Kms\Core\Content\Repository\ContentRepository;
use Symfony\Component\String\Slugger\SluggerInterface;

class CheckAndGenerateUniqueSlug
{
    private Content $content;
    private string $originalSlug;

    public function __construct(
        private readonly SluggerInterface $slugger,
        private readonly ContentRepository $contentRepository,
    ) {
    }

    public function process(Content $content): void
    {
        $this->content = $content;
        $source = '' == $content->getSlug() ? $content->getTitle() : $content->getSlug();
        $this->originalSlug = (string) $this->slugger->slug($source ?? '')->lower();
        $content->setSlug($this->checkAndGenerateUniqueSlug($this->originalSlug));
    }

    private function checkAndGenerateUniqueSlug(string $slug, int $attempt = 1): string
    {
        if (0 < $this->contentRepository->countBySlug($slug, (string) $this->content->getId())) {
            $newSlug = $this->originalSlug.'-'.$attempt;
            ++$attempt;

            return $this->checkAndGenerateUniqueSlug($newSlug, $attempt);
        }

        return $slug;
    }
}
