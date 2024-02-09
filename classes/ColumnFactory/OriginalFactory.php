<?php

declare(strict_types=1);

namespace AC\ColumnFactory;

use AC\Column;
use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollectionBuilder;
use AC\Setting\Config;
use AC\Setting\Formatter\NullFormatter;

class OriginalFactory implements ColumnFactory
{

    private $type;

    private $label;

    private $builder;

    public function __construct(string $type, string $label, ComponentCollectionBuilder $builder)
    {
        $this->type = $type;
        $this->label = $label;
        $this->builder = $builder;
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
            $this->builder->add_defaults()->build($config),
            'default'
        );
    }

}