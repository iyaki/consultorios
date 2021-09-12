<?php

declare(strict_types=1);

namespace Consultorios\Core\Agendas;

use Consultorios\Core\Agendas\Infrastructure\EspecialidadRepositoryDoctrine;
use Consultorios\Core\Agendas\UseCases\Especialidades;
use Consultorios\Core\Common\CommonContainer;
use Consultorios\Core\Common\Infrastructure\DoctrineSettings;

final class AgendasContainer
{
    public static function getCasosDeUsoEspecialidades(): Especialidades
    {
        $dbSettings = require __DIR__ . '/../config/database.php';
        $dbal = CommonContainer::dbal(new DoctrineSettings(
            $dbSettings,
            [__DIR__ . '/config/mappings'],
            true
        ));

        return new Especialidades(
            $dbal->unitOfWork(),
            new EspecialidadRepositoryDoctrine(
                $dbal->entityManager()
            )
        );
    }
}