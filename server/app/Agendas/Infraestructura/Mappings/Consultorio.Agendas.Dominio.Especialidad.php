<?php

declare(strict_types=1);

use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;

$builder = new ClassMetadataBuilder($metadata);

$builder
    ->setTable('agendas_especialidades')
    ->createField('id', 'integer')
        ->makePrimaryKey()
        ->build()
    ->addField('nombre', 'string')
;
