<?php

declare(strict_types=1);

namespace Consultorios\DevToolsSettings;

use Rector\Core\Configuration\Option;
use Rector\Set\ValueObject\SetList;
use Rector\Set\ValueObject\LevelSetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

if (! \function_exists('\Consultorios\DevToolsSettings\getRectorConfigurator')) {
    /**
     * @param string[] $includePaths
    */
    function getRectorConfigurator(array $includePaths): callable
    {
        return static function (ContainerConfigurator $containerConfigurator) use ($includePaths): void {
            $parameters = $containerConfigurator->parameters();
            $parameters->set(Option::PATHS, $includePaths);
            $parameters->set(Option::CACHE_DIR, \sys_get_temp_dir() . '/.rector_cache');
            $parameters->set(Option::AUTO_IMPORT_NAMES, true);
            $parameters->set(Option::IMPORT_SHORT_CLASSES, false);
            // $parameters->set(Option::APPLY_AUTO_IMPORT_NAMES_ON_CHANGED_FILES_ONLY, true);
            $parameters->set(Option::PARALLEL, true);
            $parameters->set(Option::ENABLE_EDITORCONFIG, true);

            $containerConfigurator->import(SetList::CODE_QUALITY);
            $containerConfigurator->import(SetList::CODING_STYLE);
            $containerConfigurator->import(SetList::DEAD_CODE);
            $containerConfigurator->import(SetList::ORDER);
            $containerConfigurator->import(LevelSetList::UP_TO_PHP_80);
            $containerConfigurator->import(SetList::PSR_4);
            $containerConfigurator->import(SetList::TYPE_DECLARATION);
            $containerConfigurator->import(SetList::TYPE_DECLARATION_STRICT);
            $containerConfigurator->import(SetList::EARLY_RETURN);

            $services = $containerConfigurator->services();
            $services->set(\Rector\Privatization\Rector\Class_\ChangeGlobalVariablesToPropertiesRector::class);
            $services->set(\Rector\Privatization\Rector\Property\ChangeReadOnlyPropertyWithDefaultValueToConstantRector::class);
            $services->set(\Rector\Privatization\Rector\Class_\ChangeReadOnlyVariableWithDefaultValueToConstantRector::class);
            $services->set(\Rector\Privatization\Rector\Class_\RepeatedLiteralToClassConstantRector::class);
            $services->set(\Rector\Privatization\Rector\MethodCall\PrivatizeLocalGetterToPropertyRector::class);
            $services->set(\Rector\Privatization\Rector\Property\PrivatizeFinalClassPropertyRector::class);
            $services->set(\Rector\Privatization\Rector\ClassMethod\PrivatizeFinalClassMethodRector::class);
        };
    }
}
