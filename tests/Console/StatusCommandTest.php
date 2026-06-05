<?php

declare(strict_types=1);

namespace Tests\Console;

use Hibla\Migrations\Console\StatusCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

describe('StatusCommand', function () {
    $tempDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'hibla_status_command_test_' . uniqid();

    beforeEach(function () use ($tempDir) {
        if (! is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }
    });

    afterEach(function () use ($tempDir) {
        deleteDirectory($tempDir);
    });

    it('reports success when all configuration files and .env exist', function () use ($tempDir) {
        $application = new Application();
        $command = new StatusCommand();
        $application->addCommand($command);

        setPrivateProperty($command, 'projectRoot', $tempDir);

        file_put_contents($tempDir . '/hibla-database.php', '<?php return [];');
        file_put_contents($tempDir . '/hibla-migrations.php', '<?php return [];');
        file_put_contents($tempDir . '/.env', 'DB_CONNECTION=mysql');

        $tester = new CommandTester($command);
        $exitCode = $tester->execute([]);

        expect($exitCode)->toBe(0);

        $output = $tester->getDisplay();
        expect($output)->toContain('✓ Found')
            ->and($output)->toContain('All configured!')
            ->and($output)->not->toContain('✗ Missing')
        ;
    });

    it('reports failure and outputs instructions when configuration files are missing', function () use ($tempDir) {
        $application = new Application();
        $command = new StatusCommand();
        $application->addCommand($command);

        setPrivateProperty($command, 'projectRoot', $tempDir);

        $tester = new CommandTester($command);
        $exitCode = $tester->execute([]);

        expect($exitCode)->toBe(1);

        $output = $tester->getDisplay();
        expect($output)->toContain('✗ Missing')
            ->and($output)->toContain('Run: ./vendor/bin/hibla-query-builder init')
            ->and($output)->not->toContain('All configured!')
        ;
    });
});
