<?php

declare(strict_types=1);

namespace AC\Build;

use AC\Build\Exception\RouterException;

final class Router
{

    private array $tasks;

    private array $args;

    public function __construct(array $tasks)
    {
        global $argv;

        $this->tasks = $tasks;
        $this->args = $argv;
    }

    public function getTask(): string
    {
        $task = $this->args[1] ?? null;

        if ( ! $task) {
            throw RouterException::missingTask();
        }

        if ( ! isset($this->tasks[$task])) {
            throw RouterException::taskNotDefined($task);
        }

        return $this->tasks[$task];
    }

}