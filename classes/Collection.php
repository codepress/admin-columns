<?php

declare(strict_types=1);

namespace AC;

use Countable;
use Iterator;

class Collection implements Iterator, Countable
{

    /**
     * @var array
     */
    protected $items;

    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    public function all(): array
    {
        return $this->items;
    }

    public function has($key): bool
    {
        return isset($this->items[$key]);
    }

    public function put($key, $value): self
    {
        $this->items[$key] = $value;

        return $this;
    }

    public function push($value): self
    {
        $this->items[] = $value;

        return $this;
    }

    public function get($key, $default = null)
    {
        if ($this->has($key)) {
            return $this->items[$key];
        }

        return $default;
    }

    public function __get($key)
    {
        return $this->get($key);
    }

    public function rewind(): void
    {
        reset($this->items);
    }

    public function first()
    {
        return reset($this->items);
    }

    public function current()
    {
        return current($this->items);
    }

    public function key()
    {
        return key($this->items);
    }

    public function next(): void
    {
        next($this->items);
    }

    public function get_copy(): array
    {
        return $this->items;
    }

    public function valid(): bool
    {
        return key($this->items) !== null;
    }

    public function count(): int
    {
        return count($this->items);
    }

    /**
     * Filter collection items
     */
    public function filter(): self
    {
        return new Collection(ac_helper()->array->filter($this->items));
    }

    /**
     * Limit array to max number of items
     */
    public function limit(int $length): int
    {
        $count = $this->count();

        if (0 < $length) {
            $this->items = array_slice($this->items, 0, $length);
        }

        return $count - $this->count();
    }

    public function implode(string $glue = ''): string
    {
        return implode($glue, $this->items);
    }

}