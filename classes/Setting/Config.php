<?php

declare(strict_types=1);

namespace AC\Setting;

final class Config
{

    private $config;

    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    public function has($key): bool
    {
        return isset($this->config[$key]);
    }

    public function get($key, $default = null)
    {
        return $this->config[$key] ?? $default;
    }

    public function all(): array
    {
        return $this->config;
    }

}