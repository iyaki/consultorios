<?php

declare(strict_types=1);

namespace Consultorios\DevToolsSettings;

use Rector\Config\RectorConfig;
use Rector\Core\Configuration\Option;
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
            $rectorConfig->importShortClasses();
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

            $rectorConfig->rule(\Rector\Privatization\Rector\Class_\ChangeGlobalVariablesToPropertiesRector::class);
            $rectorConfig->rule(\Rector\Privatization\Rector\Property\ChangeReadOnlyPropertyWithDefaultValueToConstantRector::class);
            $rectorConfig->rule(\Rector\Privatization\Rector\Class_\ChangeReadOnlyVariableWithDefaultValueToConstantRector::class);
            $rectorConfig->rule(\Rector\Privatization\Rector\MethodCall\PrivatizeLocalGetterToPropertyRector::class);
            $rectorConfig->rule(\Rector\Privatization\Rector\Property\PrivatizeFinalClassPropertyRector::class);
            $rectorConfig->rule(\Rector\Privatization\Rector\ClassMethod\PrivatizeFinalClassMethodRector::class);
        };
    }
}
