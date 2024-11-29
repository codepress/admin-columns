<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

final class PostTypeFactory
{

    public function create(bool $show_any): PostType
    {
        return new PostType($show_any);
    }

}