<?php

declare(strict_types=1);

namespace ComposerScripts;

final class StaticAnalysis
{
    public static function check(): void
    {
        self::baseStyleCommand(false, true, false);
    }

    public static function checkPreCommit(): void
    {
        $returnStatusCode = self::baseStyleCommand(false, false, true);
        exit($returnStatusCode);
    }

    public static function fix(): void
    {
        self::baseStyleCommand(true, false, false);
    }

    private static function baseStyleCommand(
        bool $fix,
        bool $showDiff,
        bool $onlyStagedFiles
    ): int {
        $returnStatusCode = 0;

        $fixArgument = $fix ? '--alter' : '';
        $diffArgument = $showDiff ? '' : '--no-diff --show-snippet=false --no-suggestions --no-progress';
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
            tools/psalm --threads=8 {$diffArgument} {$fixArgument} {$files}
        fi
        SHELL,
            $returnStatusCode
        );

        return $returnStatusCode;
    }
}
