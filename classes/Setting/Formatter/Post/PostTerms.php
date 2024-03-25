<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\Post;

use AC\Exception\ValueNotFoundException;
use AC\Setting\Formatter;
use AC\Setting\Type\Value;
use AC\Setting\ValueCollection;

class PostTerms implements Formatter
{

    private $taxonomy;

    public function __construct(string $taxonomy)
    {
        $this->taxonomy = $taxonomy;
    }

    public function format(Value $value)
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