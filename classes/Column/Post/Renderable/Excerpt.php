<?php

declare(strict_types=1);

namespace AC\Column\Post\Renderable;

class Excerpt extends Formatted
{

    protected function get_pre_formatted_value($id): string
    {
        return ac_helper()->post->excerpt((int)$id);
    }

}