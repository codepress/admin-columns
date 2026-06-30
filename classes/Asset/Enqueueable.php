<?php

declare(strict_types=1);

namespace AC\Asset;

abstract class Enqueueable
{
    protected string $handle;

    protected ?Location $location;

    protected array $dependencies;

    public function __construct(string $handle, ?Location $location = null, array $dependencies = [])
    {
        $this->handle = $handle;
        $this->location = $location;
        $this->dependencies = $dependencies;
    }

    public function get_handle(): string
    {
        return $this->handle;
    }

    protected function get_version(): ?int
    {
        if (null === $this->location) {
            return null;
        }

        $path = $this->location->get_path();

        if (! file_exists($path)) {
            return null;
        }

        $mtime = filemtime($path);

        return false === $mtime ? null : $mtime;
    }

    abstract public function register(): void;

    abstract public function enqueue(): void;

}
