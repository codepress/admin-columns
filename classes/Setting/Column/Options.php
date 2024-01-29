<?php

declare(strict_types=1);

namespace AC\Setting\Column;

use AC\Iterator;
use AC\Setting\Config;

class Options extends Iterator
{

    public function __construct(array $data = [])
    {
        array_map([$this, 'add'], $data);
    }

    public function add(Config $rule): void
    {
        $this->data[] = $rule;
    }

    public function current(): Config
    {
        return parent::current();
    }
}