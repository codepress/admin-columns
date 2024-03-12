<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Setting\Formatter\PositionAware;
use Countable;
use Iterator;

final class FormatterCollection implements Iterator, Countable
{

    private $formatters;

    /**
     * @var int
     */
    private $position;

    /**
     * @var int
     */
    private $formatter;

    public function __construct(array $formatters = [])
    {
        array_map([$this, 'add'], $formatters);
        $this->rewind();
    }

    public function add(Formatter $formatter): void
    {
        $position = 0;

        if ( $formatter instanceof PositionAware ) {
            $position = $formatter->get_position();
        }

        $this->data[ $position ][] = $formatter;
    }

    public function current() : Formatter
    {
        return current($this->data);
    }

    public function next(): void
    {
        next($this->data);
    }

    public function key(): int
    {
        return key($this->data);
    }

    public function valid(): bool
    {
        return key($this->data) !== null;
    }

    public function rewind(): void
    {
        reset($this->formatters);
        $this->position = key( $this->formatters );
        $this->formatter = 0;
    }

    public function count(): int
    {
        return count($this->data);
    }

}