<?php

declare(strict_types=1);

namespace AC\Formatter\Term;

use AC\Formatter;
use AC\Type\PostTypeSlug;
use AC\Type\Value;
use WP_Term;

class TermLink implements Formatter
{

    private string $link_to;

    private ?PostTypeSlug $post_type;

    public function __construct(string $link_to, ?PostTypeSlug $post_type = null)
    {
        $this->link_to = $link_to;
        $this->post_type = $post_type;
    }

    public function format(Value $value): Value
    {
        $link = null;

        switch ($this->link_to) {
            case 'filter':
                $term = get_term($value->get_id());

                if ($term instanceof WP_Term) {
                    $link = ac_helper()->taxonomy->get_filter_by_term_url(
                        $term,
                        (string)$this->post_type
                    );

                    $link = apply_filters(
                        'ac/formatter/term/link',
                        $link,
                        $term,
                        (string)$this->post_type
                    );
                }
                break;
            case 'edit' :
                $term = get_term($value->get_id());
                $link = get_edit_term_link($term, $term->taxonomy);

                break;
        }

        return $link
            ? $value->with_value(ac_helper()->html->link($link, (string)$value))
            : $value;
    }

}