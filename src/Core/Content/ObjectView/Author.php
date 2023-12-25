<?php

namespace Kms\Core\Content\ObjectView;

class Author
{
    public function __construct(
        public readonly string $fullName,
        public readonly string $role,
    ) {
    }
}
