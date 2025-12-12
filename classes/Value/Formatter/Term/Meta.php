<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Term;

use AC;
use AC\MetaType;

class Meta extends AC\Value\Formatter\Meta
{

    public function __construct(string $meta_key)
    {
        parent::__construct(MetaType::create_term_meta(), $meta_key);
    }

}