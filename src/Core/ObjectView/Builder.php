<?php

namespace Kms\Core\ObjectView;

class Builder implements BuilderInterface
{
    /**
     * @param iterable<BuilderInterface> $builders
     */
    public function __construct(private readonly iterable $builders)
    {
    }

    /**
     * @param array $data
     * @param object|null $entity
     * @param string $objectViewFqcn
     * @return object
     */
    public function build(string $objectViewFqcn, object $entity = null, array $data = []): object
    {
        if (!class_exists($objectViewFqcn)) {
            throw new \InvalidArgumentException(sprintf('The class "%s" does not exist', $objectViewFqcn));
        }

        /**
         * @var BuilderInterface $builder
         */
        foreach ($this->builders as $builder) {
            if ($builder->supports($objectViewFqcn)) {
                return $builder->build($objectViewFqcn, $entity, $data);
            }
        }

        throw new \InvalidArgumentException(sprintf('No builder found for "%s"', $objectViewFqcn));
    }

    public function supports(string $objectViewFqcn): bool
    {
        return false;
    }
}
