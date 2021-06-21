<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\EasyCodingStandard\ValueObject\Option;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PATHS, [__DIR__ . '/app']);
    $parameters->set(Option::SKIP, [
        __DIR__ . '/tools',
        __DIR__ . '/ecs.php',
        __DIR__ . '/rector.php',
    ]);
    $parameters->set(Option::CACHE_DIRECTORY, '.ecs_cache');
    $parameters->set(Option::LINE_ENDING, "\n");

    $containerConfigurator->import(SetList::PSR_12);
    $containerConfigurator->import(SetList::CLEAN_CODE);
    $containerConfigurator->import(SetList::COMMON);
};
