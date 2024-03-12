<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\Term;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;
use WP_Term;

class TermProperty implements Formatter
{

    private $property;

    public function __construct(string $property)
    {
        $this->property = $property;
    }

    public function format(Value $value): Value
    {
        $term = get_term($value->get_id());

        if ( ! $term instanceof WP_Term) {
            return new Value(null);
        }

        return $term->{$this->property};
    }

}