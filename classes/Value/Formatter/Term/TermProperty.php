<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Term;

use AC\Exception\ValueNotFoundException;
use AC\Setting\Formatter;
use AC\Type\Value;
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
            throw new ValueNotFoundException();
        }

        return $value->with_value($term->{$this->property} ?? '');
    }

}