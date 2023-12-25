<?php

namespace Kms\Website\Content\Controller;

use Kms\Core\Http\QueryParser\FilterQueryParser;
use Kms\Core\Http\QueryParser\PageQueryParser;
use Kms\Core\Http\QueryParser\SortQueryParser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ShowController extends AbstractController
{
    public function __invoke(
        Request $request,
        PageQueryParser $pageQueryParser,
        SortQueryParser $sortQueryParser,
        FilterQueryParser $postFiltersQueryParser,
        string $slug = null,
    ): Response {
        return $this->renderContent(
            $request,
            $pageQueryParser,
            $sortQueryParser,
            $postFiltersQueryParser,
            $slug,
        );
    }
}
