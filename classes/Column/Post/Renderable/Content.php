<?php

declare(strict_types=1);

namespace AC\Column\Post\Renderable;

// TODO turn into formatter
class Content extends Formatted
{

    public function get_pre_formatted_value($id): string
    {
        return (string)get_post_field('post_content', $id, 'raw');
    }

}