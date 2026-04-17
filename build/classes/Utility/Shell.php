<?php

declare(strict_types=1);

namespace AC\Build\Utility;

use League\CLImate\CLImate;

final class Shell
{

    public static function execute(string $command, ...$args): string
    {
        if ($args) {
            $command = vsprintf($command, $args);
        }

        return (string)shell_exec($command);
    }

    public static function out(string $output, ...$args): void
    {
        static $cli = null;

        if ($cli === null) {
            $cli = new CLImate();
        }

        if ($args) {
            $output = vsprintf($output, $args);
        }

        $cli->out($output);
    }

    public static function error(string $output, ...$args): void
    {
        self::out(sprintf('<red>%s</red>', $output), ...$args);
    }

    public static function newLine(): void
    {
        self::out('');
    }

}