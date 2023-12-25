<?php

namespace Kms\Admin\Setting\Controller;

use Kms\Core\Content\Enum\Type;
use Kms\Core\Content\Repository\PageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CacheController extends AbstractController
{
    public function __construct(
        private readonly TagAwareAdapterInterface $kmsCache,
        private readonly PageRepository $pageRepository,
    ) {
    }

    public function __invoke(Request $request, string $type = null): Response
    {
        if ($request->isMethod('POST') && null !== $type) {
            $this->kmsCache->invalidateTags([$type]);

            if (Type::POST->value == $type && null !== $blogPage = $this->pageRepository->findBlogPage()) {
                $this->kmsCache->invalidateTags([sprintf('page_%s', $blogPage->getSlug())]);
            }

            return $this->redirect($request->server->get('HTTP_REFERER'));
        }

        return $this->render('@Kms/admin/setting/cache.html.twig');
    }
}
