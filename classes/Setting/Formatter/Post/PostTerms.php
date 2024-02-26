<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\Post;

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

    public function format(Value $value): Value
    {
        $terms = get_the_terms($value->get_id(), $this->taxonomy);

        if ( ! $terms || is_wp_error($terms)) {
            return $value->with_value(false);
        }

        $collection = new ValueCollection();

        foreach ($terms as $term) {
            $collection->add(
                new Value($term->term_id, $term->name)
            );
        }

        return $value->with_value($collection);
    }

}