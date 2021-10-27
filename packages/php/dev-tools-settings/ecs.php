<?php

declare(strict_types=1);

namespace Consultorios\DevToolsSettings;

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\EasyCodingStandard\ValueObject\Option;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

if (! \function_exists('\Consultorios\DevToolsSettings\getECSConfigurator')) {
    /**
     * @param string[] $includePaths
     * @param string[] $excludePaths
    */
    function getECSConfigurator(array $includePaths, array $excludePaths = []): callable
    {
        return static function (ContainerConfigurator $containerConfigurator) use ($includePaths, $excludePaths): void {
            $parameters = $containerConfigurator->parameters();
            $parameters->set(Option::PATHS, $includePaths);
            $parameters->set(Option::SKIP, $excludePaths);
            $parameters->set(Option::PARALLEL, true);
            $parameters->set(Option::CACHE_DIRECTORY, \sys_get_temp_dir() . '/.ecs_cache');
            $parameters->set(Option::LINE_ENDING, "\n");
            $parameters->set(Option::SKIP, [
                \PhpCsFixer\Fixer\PhpUnit\PhpUnitStrictFixer::class,
            ]);

            $containerConfigurator->import(SetList::PSR_12);
            $containerConfigurator->import(SetList::CLEAN_CODE);
            $containerConfigurator->import(SetList::COMMON);
        };
    }
}
