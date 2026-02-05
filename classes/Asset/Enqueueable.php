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
        $path = $this->location->get_path();

        return file_exists($path)
            ? filemtime($path)
            : null;
    }

    abstract public function register(): void;

    abstract public function enqueue(): void;

}