<?php

declare(strict_types=1);

namespace AC\Formatter\Term;

use AC\Exception\ValueNotFoundException;
use AC\Formatter;
use AC\Type\Value;
use WP_Term;

class TermProperty implements Formatter
{

    private string $field;

    public function __construct(string $field)
    {
        $this->field = $field;
    }

    public function format(Value $value): Value
    {
        $term = get_term($value->get_id());

        if ( ! $term instanceof WP_Term) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        $field = $term->{$this->field} ?? null;

        if (null === $field) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        switch ($this->field) {
            case 'parent':
                return new Value((int)$field);
            default:
                return $value->with_value($field);
        }
    }
}