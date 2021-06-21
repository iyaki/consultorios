<?php

declare(strict_types=1);

namespace ComposerScripts;

final class Refactor
{
    public static function run(): void
    {
        self::baseStyleCommand(false, true, false);
    }

    public static function dry(): void
    {
        $returnStatusCode = self::baseStyleCommand(true, true, false);
        exit($returnStatusCode);
    }

    public static function postcommit(): void
    {
        self::baseStyleCommand(false, false, true);
    }

    private static function baseStyleCommand(
        bool $dry,
        bool $showDiff,
        bool $onlyLastCommit
    ): int {
        $returnStatusCode = 0;

        $dryArgument = $dry ? '--dry-run' : '';
        $diffArgument = $showDiff ? '' : '--no-diffs --no-progress-bar';
        $filesArgument = (
            $onlyLastCommit
            ? 'git diff-tree -r --name-only --no-commit-id HEAD'
            : 'git diff HEAD --name-only --diff-filter=ACMRTUXB'
        );
        $argumentsOffset = 'run' === $_SERVER['argv'][1] ? 3 : 2;
        $files = (
            count($_SERVER['argv']) > $argumentsOffset + 1
            ? implode(' ', array_slice($_SERVER['argv'], $argumentsOffset))
            : '${CHANGED_FILES}'
        );

        passthru(<<<SHELL
        IFS='
        '
        CHANGED_FILES=$({$filesArgument})
        CHANGED_FILES=$(printf "\${CHANGED_FILES}")
        if [ "\${CHANGED_FILES}" != "" ]
        then
            vendor/bin/rector process {$diffArgument} {$dryArgument} {$files}
        fi
        SHELL,
            $returnStatusCode
        );

        return $returnStatusCode;
    }
}
