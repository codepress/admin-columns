<?php

declare(strict_types=1);

namespace AC\Setting\Formatter\Term;

use AC\Setting\Formatter;
use AC\Setting\Type\Value;
use AC\Type\PostTypeSlug;
use WP_Term;

class TermLink implements Formatter
{

    private $link_to;

    private $post_type;

    public function __construct(string $link_to, PostTypeSlug $post_type = null)
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
                        $this->post_type
                    );
                }
                break;
            case 'edit' :
                $link = get_edit_term_link($value->get_id());

                break;
        }

        return $link
            ? $value->with_value(ac_helper()->html->link($link, $value->get_value()))
            : $value;
    }

}