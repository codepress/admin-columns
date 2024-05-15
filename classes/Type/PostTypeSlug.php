<?php

declare(strict_types=1);

namespace AC\Type;

class PostTypeSlug
{

    private $post_type;

    public function __construct(string $post_type)
    {
        $this->post_type = $post_type;
    }

    public function __toString(): string
    {
        return $this->post_type;
    }

}