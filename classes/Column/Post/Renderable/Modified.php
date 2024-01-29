<?php

declare(strict_types=1);

namespace AC\Column\Post\Renderable;

class Modified extends Formatted
{

    protected function get_pre_formatted_value($id):string
    {
        return (string)get_post_field('post_modified', $id);
    }

}