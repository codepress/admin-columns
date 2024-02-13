<?php

declare(strict_types=1);

namespace AC\Setting\Formatter;

use AC\Setting\Formatter;

final class AggregateBuilder
{

    /**
     * @var Formatter[]
     */
    private $formatters = [];

    public function add(Formatter $formatter): self
    {
        $this->formatters[] = $formatter;

        return $this;
    }

    public function prepend(Formatter $formatter): self
    {
        array_unshift($this->formatters, $formatter);

        return $this;
    }

    public function build(): Aggregate
    {
        return new Aggregate($this->formatters);
    }

}