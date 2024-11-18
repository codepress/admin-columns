<?php

namespace AC\Request;

final class Parameters
{

    private array $parameters;

    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    public function all(): array
    {
        return $this->parameters;
    }

    public function get(string $key, $default = null)
    {
        return array_key_exists($key, $this->parameters)
            ? $this->parameters[$key]
            : $default;
    }

    public function set(string $key, $value): void
    {
        $this->parameters[$key] = $value;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->parameters);
    }

    public function remove(string $key): void
    {
        unset($this->parameters[$key]);
    }

    public function merge(array $input): void
    {
        $this->parameters = array_merge($this->parameters, $input);
    }

    /**
     * Wrapper account filter_var
     */
    public function filter(string $key, $default = null, int $filter = FILTER_DEFAULT, $options = 0)
    {
        $value = $this->get($key, $default);

        return filter_var($value, $filter, $options);
    }

    public function count(): int
    {
        return count($this->parameters);
    }

}