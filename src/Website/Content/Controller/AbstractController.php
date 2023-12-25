<?php

namespace Kms\Website\Content\Controller;

use Kms\Core\Content\Builder\ContentCacheKeyBuilder;
use Kms\Core\Content\Enum\Type;
use Kms\Core\Content\UseCase\GetContentBySlugAndType;
use Kms\Core\Http\QueryParser\FilterQueryParser;
use Kms\Core\Http\QueryParser\PageQueryParser;
use Kms\Core\Http\QueryParser\SortQueryParser;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as SymfonyAbstractController;
use Symfony\Component\Cache\Adapter\TagAwareAdapterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

abstract class AbstractController extends SymfonyAbstractController
{
    public function __construct(
        protected readonly SerializerInterface $serializer,
        protected readonly TagAwareAdapterInterface $kmsCache,
        protected readonly GetContentBySlugAndType $getContentBySlugAndType,
        protected readonly LoggerInterface $logger,
    ) {
    }

    protected function renderError(int $statusCode): Response
    {
        return $this->render(
            '@Kms/website/error/'.$statusCode.'.html.twig',
            [],
            new Response('', $statusCode)
        );
    }

    protected function renderContent(
        Request $request,
        PageQueryParser $pageQueryParser,
        SortQueryParser $sortQueryParser,
        FilterQueryParser $filterQueryParser,
        string $slug = null,
    ): Response {
        try {
            $type = Type::fromRouteName($request->attributes->get('_route'));
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
                $this->kmsCache->deleteItem($cacheKey);

                return $this->renderError(Response::HTTP_NOT_FOUND);
            }

            $jsonContent = $this->serializer->serialize($content, 'json');
            $template = sprintf('@Kms/website/%s/default.html.twig', $type->value);
            if ($content->extra['isHomePage'] ?? null) {
                $template = '@Kms/website/page/home.html.twig';
            }

            return $this->render(
                $template,
                compact('content', 'jsonContent')
            );
        } catch (\Throwable $e) {
            dd($e);
            $this->logger->error($e, [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return $this->renderError(Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
