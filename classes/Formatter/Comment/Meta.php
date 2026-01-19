<?php

declare(strict_types=1);

namespace AC\Formatter\Comment;

use AC;
use AC\MetaType;

class Meta extends AC\Formatter\Meta
{

    public function __construct(string $meta_key)
    {
        parent::__construct(MetaType::create_comment_meta(), $meta_key);
    }

}