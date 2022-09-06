<?php

declare(strict_types=1);

namespace Consultorios\Core\Agendas;

use Consultorios\Core\Agendas\Infrastructure\EspecialidadRepositoryDoctrine;
use Consultorios\Core\Agendas\UseCases\Especialidades;
use Consultorios\Core\Common\CommonContainer;
use Consultorios\ORM\ORM;

final class AgendasContainer
{
    public static function getCasosDeUsoEspecialidades(): Especialidades
    {
        $dbSettings = require __DIR__ . '/../config/database.php';
        $orm = new ORM(
            $dbSettings,
            [__DIR__ . '/../config/mappings'],
            CommonContainer::devMode()
        );

        return new Especialidades(
            $orm->unitOfWork,
            new EspecialidadRepositoryDoctrine(
                $orm->entityManager
            )
        );
    }
}
