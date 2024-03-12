<?php

declare(strict_types=1);

namespace AC\Setting;

use Countable;

final class FormatterCollection extends Collection implements Countable
{

    public function __construct(array $formatters = [])
    {
        array_map([$this, 'add'], $formatters);
    }

    public function add(Formatter $formatter): void
    {
        $this->data[] = $formatter;
    }

    public function current(): Formatter
    {
        return parent::current();
    }

    public function count(): int
    {
        return count($this->data);
    }

}