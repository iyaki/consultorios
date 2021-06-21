<?php

declare(strict_types=1);

namespace Consultorio\Agendas\Domain;

trait IdTrait
{
    public function __construct(
        private string $uuid
    ) {
    }

    public function __toString()
    {
        return $this->uuid;
    }
}
