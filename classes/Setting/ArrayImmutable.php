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

    public function has($name): bool
    {
        return isset($this->values[$name]);
    }

    public function get($name)
    {
        return $this->values[$name] ?? null;
    }

}