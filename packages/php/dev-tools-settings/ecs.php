<?php

declare(strict_types=1);

namespace Consultorios\DevToolsSettings;

use Symplify\EasyCodingStandard\Config\ECSConfig;
    use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

if (! \function_exists('\Consultorios\DevToolsSettings\getECSConfigurator')) {
    /**
     * @param string[] $includePaths
     * @param string[] $excludePaths
    */
    function getECSConfigurator(array $includePaths, array $excludePaths = []): callable
    {
        return static function (ECSConfig $ecsConfig) use ($includePaths, $excludePaths): void {
            $ecsConfig->paths($includePaths);
            $ecsConfig->skip(array_merge(
                $excludePaths,
                [
                    \PhpCsFixer\Fixer\PhpUnit\PhpUnitStrictFixer::class,
                ]
            ));
            $ecsConfig->lineEnding("\n");

            $ecsConfig->sets([
                SetList::PSR_12,
                SetList::CLEAN_CODE,
                SetList::COMMON
            ]);

            $ecsConfig->cacheDirectory('.ecs-cache');
        };
    }
}
