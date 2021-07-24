<?php

declare(strict_types=1);

namespace Consultorio\Agendas\Infraestructura\Mappings;

/**
 * @psalm-suppress MixedArgument
 * @psalm-suppress UndefinedGlobalVariable
 */
$builder = new \Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder($metadata);
$builder->setTable('agendas_especialidades');
$builder
    ->createField('id', 'guid')
    ->makePrimaryKey()
    ->build()
;
$builder->addField('nombre', 'string');
