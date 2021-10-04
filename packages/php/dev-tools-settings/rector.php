<?php

declare(strict_types=1);

namespace Consultorios\DevToolsSettings;

use Rector\Core\Configuration\Option;
use Rector\Set\ValueObject\SetList;
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

            $containerConfigurator->import(SetList::CODE_QUALITY);
            $containerConfigurator->import(SetList::CODING_STYLE);
            $containerConfigurator->import(SetList::DEAD_CODE);
            $containerConfigurator->import(SetList::ORDER);
            $containerConfigurator->import(SetList::PHP_70);
            $containerConfigurator->import(SetList::PHP_71);
            $containerConfigurator->import(SetList::PHP_72);
            $containerConfigurator->import(SetList::PHP_73);
            $containerConfigurator->import(SetList::PHP_74);
            $containerConfigurator->import(SetList::PHP_80);
            $containerConfigurator->import(SetList::PSR_4);
            $containerConfigurator->import(SetList::TYPE_DECLARATION);
            $containerConfigurator->import(SetList::TYPE_DECLARATION_STRICT);
            $containerConfigurator->import(SetList::EARLY_RETURN);

            $services = $containerConfigurator->services();
            $services->set(\Rector\Privatization\Rector\ClassMethod\ChangeGlobalVariablesToPropertiesRector::class);
            $services->set(\Rector\Privatization\Rector\Property\ChangeReadOnlyPropertyWithDefaultValueToConstantRector::class);
            $services->set(\Rector\Privatization\Rector\Class_\ChangeReadOnlyVariableWithDefaultValueToConstantRector::class);
            $services->set(\Rector\Privatization\Rector\Class_\RepeatedLiteralToClassConstantRector::class);
            $services->set(\Rector\Privatization\Rector\MethodCall\PrivatizeLocalGetterToPropertyRector::class);
            $services->set(\Rector\Privatization\Rector\Property\PrivatizeFinalClassPropertyRector::class);
            $services->set(\Rector\Privatization\Rector\ClassMethod\PrivatizeFinalClassMethodRector::class);
        };
    }
}
