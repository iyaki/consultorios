<?php

declare(strict_types=1);

namespace Consultorios\ORM\Fixtures;

/**
 * @psalm-external-mutation-free
 */
final class EntityDummy
{
    public function __construct(
        private string $id,
        private string $property
    )
    {}

    /**
     * @psalm-mutation-free
     */
    public function id(): string
    {
        return $this->id;
    }

    public function setProperty(string $newValue): void
    {
        $this->property = $newValue;
    }

    /**
     * @psalm-mutation-free
     */
    public function property(): string
    {
        return $this->property;
    }

    /**
     * @codeCoverageIgnore
     */
    public function __clone()
    {
        throw new \Exception('Cloning this class is not allowed');
    }

    /**
     * @codeCoverageIgnore
     */
    public function __sleep()
    {
        throw new \Exception('This class can\'t be serialized');
    }
}
