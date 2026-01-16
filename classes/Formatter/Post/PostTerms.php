<?php

declare(strict_types=1);

namespace AC\Formatter\Post;

use AC\Exception\ValueNotFoundException;
use AC\Formatter;
use AC\Type\Value;
use AC\Type\ValueCollection;

class PostTerms implements Formatter
{

    private string $taxonomy;

    public function __construct(string $taxonomy)
    {
        $this->taxonomy = $taxonomy;
    }

    public function format(Value $value): ValueCollection
    {
        $terms = get_the_terms($value->get_id(), $this->taxonomy);

        if ( ! $terms || is_wp_error($terms)) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        $collection = new ValueCollection($value->get_id());

        foreach ($terms as $term) {
            $collection->add(
                new Value($term->term_id, $term->name)
            );
        }

        return $collection;
    }

}