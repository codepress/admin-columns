<?php

declare(strict_types=1);

namespace AC\Column;

use AC;
use AC\Setting\Config;

class Context implements AC\Setting\Context
{
    protected Config $config;

    private string $type_label;

    public function __construct(Config $config, string $type_label)
    {
        $this->config = $config;
        $this->type_label = $type_label;
    }

    public function get_type(): string
    {
        return $this->get('type');
    }

    public function get_name(): string
    {
        return $this->get('name', '');
    }

    public function get_label(): string
    {
        return $this->get('label', '');
    }

    public function get_type_label(): string
    {
        return $this->type_label;
    }

    public function has(string $key): bool
    {
        return $this->config->has($key);
    }

    public function get(string $key, $default = null)
    {
        return $this->config->get($key, $default);
    }

    public function all(): array
    {
        return $this->config->all();
    }

}
