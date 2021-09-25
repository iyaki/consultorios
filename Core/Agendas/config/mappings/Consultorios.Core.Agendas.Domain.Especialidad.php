<?php

declare(strict_types=1);

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
