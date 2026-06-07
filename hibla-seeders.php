<?php

declare(strict_types=1);

require 'vendor/autoload.php';

return [
    /*
    |--------------------------------------------------------------------------
    | Seeders Path
    |--------------------------------------------------------------------------
    |
    | The directory where seeder files are stored and loaded from.
    | Like migrations, you can use nesting to organize them.
    |
    */
    'seeds_path' => __DIR__ . '/database/seeders',

    /*
    |--------------------------------------------------------------------------
    | Recursive Seeder Discovery
    |--------------------------------------------------------------------------
    |
    | When enabled, the seeder runner will recursively search subdirectories
    | for seeder files when executing.
    |
    */
    'recursive' => true,

    /*
    |--------------------------------------------------------------------------
    | Connection-Specific Seeder Paths
    |--------------------------------------------------------------------------
    |
    | Organize seeder files into subdirectories based on database connections.
    | When generating a seeder with the --connection flag, it will automatically
    | be placed in the mapped subdirectory.
    |
    | Example:
    |   'mysql' => 'mysql'     → database/seeders/mysql/UserSeeder.php
    |   'pgsql' => 'postgres'  → database/seeders/postgres/LogSeeder.php
    |
    */
    'connection_paths' => [
        // 'mysql' => 'mysql',
        // 'pgsql' => 'postgres',
    ],
];
