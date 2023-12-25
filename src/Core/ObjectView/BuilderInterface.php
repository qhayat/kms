<?php

namespace Kms\Core\ObjectView;

interface BuilderInterface
{
    /**
     * @param string $objectViewFqcn
     * @param object|null $entity
     * @param array $data
     * @return object
     */
    public function build(string $objectViewFqcn, object $entity = null, array $data = []): object;

    public function supports(string $objectViewFqcn): bool;
}
