<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Term;

use AC\Exception\ValueNotFoundException;
use AC\Setting\Formatter;
use AC\Type\Value;
use WP_Term;

class Slug implements Formatter
{

    public function format(Value $value)
    {
        $term = get_term($value->get_id());

        if ( ! $term instanceof WP_Term) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        return (string)apply_filters('editable_slug', $term->slug, $term);
    }

}