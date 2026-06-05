<?php

declare(strict_types=1);

use Hibla\Migrations\Console\InitCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

describe('InitCommand', function () {
    $tempDir = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'hibla_init_test_' . uniqid();

    beforeEach(function () use ($tempDir) {
        if (! is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }
    });

    afterEach(function () use ($tempDir) {
        deleteDirectory($tempDir);
    });

    it('initializes configuration files successfully in target directory', function () use ($tempDir) {
        $application = new Application();
        $command = new InitCommand();
        $application->addCommand($command);

        setPrivateProperty($command, 'targetRoot', $tempDir);

        $tester = new CommandTester($command);
        $exitCode = $tester->execute([]);

        expect($exitCode)->toBe(0);

        $output = $tester->getDisplay();
        expect($output)->toContain('Configuration created in project root: hibla-database.php')
            ->and($output)->toContain('Configuration created in project root: hibla-migrations.php')
        ;

        expect(file_exists($tempDir . '/hibla-database.php'))->toBeTrue()
            ->and(file_exists($tempDir . '/hibla-migrations.php'))->toBeTrue()
        ;
    });

    it('respects force option and overwrites files without confirmation prompt', function () use ($tempDir) {
        $application = new Application();
        $command = new InitCommand();
        $application->addCommand($command);

        setPrivateProperty($command, 'targetRoot', $tempDir);

        file_put_contents($tempDir . '/hibla-database.php', 'original content');
        file_put_contents($tempDir . '/hibla-migrations.php', 'original content');

        $tester = new CommandTester($command);

        $exitCode = $tester->execute(['--force' => true]);

        expect($exitCode)->toBe(0);

        $dbContent = file_get_contents($tempDir . '/hibla-database.php');
        expect($dbContent)->toContain('<?php')
            ->and($dbContent)->not->toContain('original content')
        ;
    });

    it('prompts before overwriting existing files when force is omitted and respects skip', function () use ($tempDir) {
        $application = new Application();
        $command = new InitCommand();
        $application->addCommand($command);

        setPrivateProperty($command, 'targetRoot', $tempDir);

        file_put_contents($tempDir . '/hibla-database.php', 'original content');
        file_put_contents($tempDir . '/hibla-migrations.php', 'original content');

        $tester = new CommandTester($command);

        $tester->setInputs(['no', 'no']);

        $exitCode = $tester->execute([]);

        expect($exitCode)->toBe(0);
        $output = $tester->getDisplay();
        expect($output)->toContain('Skipped: hibla-database.php')
            ->and($output)->toContain('Skipped: hibla-migrations.php')
        ;

        expect(file_get_contents($tempDir . '/hibla-database.php'))->toBe('original content')
            ->and(file_get_contents($tempDir . '/hibla-migrations.php'))->toBe('original content')
        ;
    });
});
