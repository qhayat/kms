<?php

namespace Kms\Api\Content\Controller;

use Kms\Core\Content\Builder\ContentCacheKeyBuilder;
use Kms\Core\Content\Enum\Type;
use Kms\Core\Content\UseCase\GetContentBySlugAndType;
use Kms\Core\Http\QueryParser\FilterQueryParser;
use Kms\Core\Http\QueryParser\PageQueryParser;
use Kms\Core\Http\QueryParser\SortQueryParser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GetController extends AbstractController
{
    public function __construct(
        private readonly GetContentBySlugAndType $getContentBySlugAndType,
        private readonly TagAwareAdapterInterface $kmsCache,
    ) {
    }

    public function __invoke(
        Request $request,
        PageQueryParser $pageQueryParser,
        SortQueryParser $sortQueryParser,
        FilterQueryParser $filterQueryParser,
        string $slug = null,
    ): JsonResponse {
        $type = Type::fromApiRouteName($request->attributes->get('_route'));
        $cacheKey = (new ContentCacheKeyBuilder($type, $slug ?? 'home'))
            ->additionalKey(hash('sha256', $request->getQueryString() ?? ''))
            ->build();
        $cacheItem = $this->kmsCache->getItem($cacheKey);
        if (false === $cacheItem->isHit()) {
            $content = $this->getContentBySlugAndType->get($type, $slug, [
                'page' => $pageQueryParser->getParams(),
                'sorts' => $sortQueryParser->getParams(),
                'filters' => $filterQueryParser->getParams(),
            ]);

            $cacheItem->set($content);
            $cacheItem->tag([$type->value, sprintf('%s_%s', $type->value, $slug ?? 'home')]);
            $this->kmsCache->save($cacheItem);
        }

        $content = $cacheItem->get();
        if (null == $content) {
            return new JsonResponse(['message' => sprintf('%s not found!', $type->value)], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse($content);
    }
}
