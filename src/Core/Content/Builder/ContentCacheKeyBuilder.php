<?php

namespace Kms\Core\Content\Builder;

use Kms\Core\Content\Enum\Type;

class ContentCacheKeyBuilder
{
    private ?string $additionalKey = null;

    public function __construct(
        private readonly Type $contentType,
        private readonly string $slug,
    ) {
    }

    public function additionalKey(string $additionalKey): self
    {
        $this->additionalKey = $additionalKey;

        return $this;
    }

    public function build(): string
    {
        $key = sprintf('%s_%s', $this->contentType->value, $this->slug);
        if (null !== $this->additionalKey) {
            $key .= sprintf('_%s', $this->additionalKey);
        }

        return $key;
    }
}
