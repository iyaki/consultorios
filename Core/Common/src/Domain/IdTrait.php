<?php

declare(strict_types=1);

namespace Consultorios\Core\Common\Domain;

trait IdTrait
{
    public function __construct(
        private string $uuid
    ) {
    }

    public function __toString(): string
    {
        return $this->uuid;
    }
}
