<?php

declare(strict_types=1);

namespace Consultorios\DevToolsSettings;

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

if (! \function_exists('\Consultorios\DevToolsSettings\getRectorConfigurator')) {
    /**
     * @param string[] $includePaths
    */
    function getRectorConfigurator(array $includePaths): callable
    {
        return static function (RectorConfig $rectorConfig) use ($includePaths): void {
            $rectorConfig->paths($includePaths);
            $rectorConfig->importNames();
            $rectorConfig->importShortClasses(false);
            $rectorConfig->parallel();

            $rectorConfig->sets([
                LevelSetList::UP_TO_PHP_81,
                SetList::CODE_QUALITY,
                SetList::CODING_STYLE,
                SetList::DEAD_CODE,
                SetList::EARLY_RETURN,
                SetList::PSR_4,
                SetList::TYPE_DECLARATION_STRICT,
                SetList::TYPE_DECLARATION,
            ]);

            $rectorConfig->rules([
                \Rector\Privatization\Rector\Class_\ChangeGlobalVariablesToPropertiesRector::class,
                \Rector\Privatization\Rector\Class_\ChangeReadOnlyVariableWithDefaultValueToConstantRector::class,
                \Rector\Privatization\Rector\ClassMethod\PrivatizeFinalClassMethodRector::class,
                \Rector\Privatization\Rector\MethodCall\PrivatizeLocalGetterToPropertyRector::class,
                \Rector\Privatization\Rector\Property\ChangeReadOnlyPropertyWithDefaultValueToConstantRector::class,
                \Rector\Privatization\Rector\Property\PrivatizeFinalClassPropertyRector::class,
            ]);
        };
    }
}
