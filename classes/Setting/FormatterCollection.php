<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Collection;
use InvalidArgumentException;
use ReturnTypeWillChange;

final class FormatterCollection extends Collection
{

    public function __construct(array $formatters = [])
    {
        array_map([$this, 'add'], $formatters);
    }

    /**
     * @param Formatter|CollectionFormatter $formatter
     */
    public function add($formatter): self
    {
        if ( ! $formatter instanceof Formatter && ! $formatter instanceof CollectionFormatter) {
            throw new InvalidArgumentException();
        }

        $this->data[] = $formatter;

        return $this;
    }

    public function with_formatter(Formatter $formatter): self
    {
        $formatters = $this->data;
        $formatters[] = $formatter;

        return new self($formatters);
    }

    public function prepend($formatter): self
    {
        if ( ! $formatter instanceof Formatter && ! $formatter instanceof CollectionFormatter) {
            throw new InvalidArgumentException();
        }

        array_unshift($this->data, $formatter);

        return $this;
    }

    public function merge(FormatterCollection $formatters): self
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