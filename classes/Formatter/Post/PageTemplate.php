<?php

declare(strict_types=1);

namespace AC\Formatter\Post;

use AC\Formatter;
use AC\Type\Value;

class PageTemplate implements Formatter
{

    private string $post_type;

    public function __construct(string $post_type)
    {
        $this->post_type = $post_type;
    }

    public function format(Value $value): Value
    {
        $template = get_post_meta($value->get_id(), '_wp_page_template', true);
        $template = array_search($template, $this->get_page_templates());

        return $value->with_value(
            $template ?: false
        );
    }

    private function get_page_templates(): array
    {
        return get_page_templates(null, $this->post_type);
    }

}