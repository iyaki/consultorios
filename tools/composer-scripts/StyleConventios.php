<?php

declare(strict_types=1);

namespace ComposerScripts;

final class StyleConventios
{
    public static function check(): void
    {
        self::baseCommand(false, true, false);
    }

    public static function checkPreCommit(): void
    {
        $returnStatusCode = self::baseCommand(false, false, true);
        exit($returnStatusCode);
    }

    public static function fix(): void
    {
        self::baseCommand(true, false, false);
    }

    private static function baseCommand(
        bool $fix,
        bool $showDiff,
        bool $onlyStagedFiles
    ): int {
        $returnStatusCode = 0;

        $fixArgument = $fix ? '--fix' : '';
        $diffArgument = $showDiff ? '' : '-q -n';
        $filesArgument = $onlyStagedFiles ? '--staged' : 'HEAD';
        $argumentsOffset = 'run' === $_SERVER['argv'][1] ? 3 : 2;
        $files = (
            count($_SERVER['argv']) > $argumentsOffset + 1
            ? implode(' ', array_slice($_SERVER['argv'], $argumentsOffset))
            : '${CHANGED_FILES}'
        );

        passthru(<<<SHELL
        IFS='
        '
        CHANGED_FILES=$(git diff {$filesArgument} --name-only --diff-filter=ACMRTUXB)
        CHANGED_FILES=$(printf "\${CHANGED_FILES}")
        if [ "\${CHANGED_FILES}" != "" ]
        then
            vendor/bin/ecs check {$diffArgument} {$fixArgument} {$files}
        fi
        SHELL,
            $returnStatusCode
        );

        return $returnStatusCode;
    }
}
