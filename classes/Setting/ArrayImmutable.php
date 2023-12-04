<?php

declare(strict_types=1);

namespace AC\Setting;

final class ArrayImmutable
{

    /**
     * @var array
     */
    private $values;

    public function __construct(array $values)
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

}