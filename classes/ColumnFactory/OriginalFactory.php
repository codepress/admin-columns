<?php

declare(strict_types=1);

namespace AC\ColumnFactory;

use AC\Column;
use AC\Column\ColumnFactory;
use AC\Setting\Builder;
use AC\Setting\Config;
use AC\Setting\Formatter\NullFormatter;

class OriginalFactory implements ColumnFactory
{

    private $type;

    private $label;

    public function __construct(string $type, string $label)
    {
        $this->type = $type;
        $this->label = $label;
    }

    public function can_create(string $type): bool
    {
        return $this->type === $type;
    }

    public function create(Config $config): Column
    {
        return new Column(
            $this->type,
            $this->label,
            new NullFormatter(),
            (new Builder())->set_defaults()->build($config),
            'default'
        );
    }

}