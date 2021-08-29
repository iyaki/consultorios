<?php

declare(strict_types=1);

namespace Consultorio\Agendas\Infraestructura\Mappings;

use Doctrine\DBAL\Types\Types;

/**
 * @psalm-suppress UndefinedGlobalVariable
 */
$builder = new \Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder($metadata);
$builder->setTable('agendas_especialidades');
$builder
    ->createField('id', Types::GUID)
    ->makePrimaryKey()
    ->build()
;
$builder->addField('nombre', Types::STRING);
