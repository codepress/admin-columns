<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Expression\Specification;
use AC\Setting\Component;
use AC\Setting\ComponentFactory;
use AC\Setting\Config;

final class WordsPerMinute implements ComponentFactory
{

    public function create(Config $config, Specification $conditions = null): Component
    {
    }

}