<?php

namespace Kms\Core\Http\QueryParser;

interface QueryParserInterface
{
    public function getParams(): array;
}
