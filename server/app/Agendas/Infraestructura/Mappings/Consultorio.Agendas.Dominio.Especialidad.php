<?php

declare(strict_types=1);

use Doctrine\ORM\Mapping\Builder\ClassMetadataBuilder;

$builder = new ClassMetadataBuilder($metadata);

$builder->setTable('agendas_especialidades');

$builder
    ->createField('id', 'guid')
    ->makePrimaryKey()
    ->build()
;

$builder->addField('nombre', 'string');
