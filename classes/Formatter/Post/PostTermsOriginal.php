<?php

declare(strict_types=1);

namespace AC\Formatter\Post;

use AC\Exception\ValueNotFoundException;
use AC\Formatter;
use AC\Type\Value;

class PostTermsOriginal implements Formatter
{

    private string $taxonomy;

    public function __construct(string $taxonomy)
    {
        $this->taxonomy = $taxonomy;
    }

    public function format(Value $value): Value
    {
        $terms = wp_get_post_terms(
            (int)$value->get_id(),
            $this->taxonomy,
            [
                'fields' => 'names',
            ]
        );

        if ( ! $terms || is_wp_error($terms)) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        return $value->with_value(
            implode(', ', $terms)
        );
    }

}