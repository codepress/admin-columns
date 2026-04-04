<?php

declare(strict_types=1);

namespace AC\Formatter\Post;

use AC\Formatter;
use AC\MetaType;

class Meta extends Formatter\Meta
{

    public function __construct(string $meta_key)
    {
        parent::__construct(MetaType::create_post_meta(), $meta_key);
    }

}