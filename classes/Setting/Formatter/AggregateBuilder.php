<?php

declare(strict_types=1);

namespace AC\Setting\Formatter;

use AC\Setting\Formatter;
use AC\Setting\ComponentCollection;
use AC\Setting\Type\Value;

// TODO David
final class AggregateBuilder
{

    /**
     * @var Formatter[]
     */
    private $formatters = [];

    public function from_settings(ComponentCollection $settings): self
    {
        $formatters = [];

        foreach ($settings as $setting) {
            if ($setting instanceof Formatter) {
                $formatters[] = $setting;
            }
        }

        return new self($formatters);
    }

    public function with_formatter( Formatter $formatter, string $position = null )
    {

    }

    // TODO David prepend vs....?

    public function add(Formatter $formatter): self
    {
        $this->data[] = $formatter;

        return $this;
    }

    public function prepend(Formatter $formatter): self
    {
        array_unshift($this->formatters, $formatter);

        return $this;
    }

    public function build() : Aggregate {
        return new Aggregate( $this->formatters );
    }

}