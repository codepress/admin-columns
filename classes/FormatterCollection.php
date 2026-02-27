<?php

declare(strict_types=1);

namespace AC;

use InvalidArgumentException;
use ReturnTypeWillChange;

class FormatterCollection extends Collection
{

    public function __construct(array $formatters = [])
    {
        array_map([$this, 'add'], $formatters);
    }

    public static function from_formatter(Formatter $formatter): self
    {
        return new self([$formatter]);
    }

    /**
     * Add a Formatter or CollectionFormatter. Once we are on PHP 8.x we can use the union operator.
     */
    public function add($formatter): self
    {
        if ( ! $formatter instanceof Formatter && ! $formatter instanceof CollectionFormatter) {
            throw new InvalidArgumentException('Expected a Formatter or CollectionFormatter.');
        }

        $this->data[] = $formatter;

        return $this;
    }

    public function with_formatter($formatter): self
    {
        $formatters = $this->data;
        $formatters[] = $formatter;

        return new self($formatters);
    }

    public function prepend($formatter): self
    {
        if ( ! $formatter instanceof Formatter && ! $formatter instanceof CollectionFormatter) {
            throw new InvalidArgumentException('Expected a Formatter or CollectionFormatter.');
        }

        array_unshift($this->data, $formatter);

        return $this;
    }

    public function merge(self $formatters): self
    {
        foreach ($formatters as $formatter) {
            $this->add($formatter);
        }

        return $this;
    }

    /**
     * @return Formatter|CollectionFormatter
     */
    #[ReturnTypeWillChange]
    public function current()
    {
        return current($this->data);
    }

}