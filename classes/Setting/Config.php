<?php

declare(strict_types=1);

namespace AC\Setting;

final class Config
{

    private $values;

    public function __construct(array $values = [])
    {
        $this->values = $values;
    }

    public function has($key): bool
    {
        return isset($this->values[$key]);
    }

    public function get($key)
    {
        return $this->values[$key] ?? null;
    }

    public function __toArray(): array
    {
        return $this->values;
    }

}