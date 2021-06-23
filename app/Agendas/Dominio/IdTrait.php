<?php

declare(strict_types=1);

namespace Consultorio\Agendas\Dominio;

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
