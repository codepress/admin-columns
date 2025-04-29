<?php

declare(strict_types=1);

namespace AC\Setting;

class Context
{

    protected Config $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function get_type(): string
    {
        return $this->get('type');
    }

    public function get_name(): string
    {
        return $this->get('name', '');
    }

    public function has(string $key): bool
    {
        return $this->config->has($key);
    }

    public function get(string $key, $default = null)
    {
        return $this->config->get($key, $default);
    }

}