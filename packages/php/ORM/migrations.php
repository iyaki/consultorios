<?php

declare(strict_types=1);

namespace Consultorios\ORM;

if (! \function_exists('\Consultorios\ORM\getMigrationsConfigs')) {

    function getMigrationsConfigs(string $baseDir): array
    {
        return [
            'table_storage' => [
                'table_name' => 'doctrine_migration_versions',
                'version_column_name' => 'version',
                'version_column_length' => 1024,
                'executed_at_column_name' => 'executed_at',
                'execution_time_column_name' => 'execution_time',
            ],

            'migrations_paths' => [
                'Consultorio\Migrations' => $baseDir . '/migrations',
            ],

            'all_or_nothing' => true,
            'check_database_platform' => true,
            'organize_migrations' => 'none',
        ];
    }

}
