<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Setting\Component;
use AC\Setting\ComponentBuilder;
use AC\Setting\Config;
use AC\Setting\Control\Input\Custom;

final class ColumnInfo
{

    private array $items;

    /**
     * @param array $items Each item: ['label' => string, 'value' => string]
     */
    public function __construct(array $items)
    {
        $this->items = $items;
    }

    public function create(Config $config): Component
    {
        $builder = new ComponentBuilder();
        $builder->set_label(__('Column Info', 'codepress-admin-columns'));
        $builder->set_type('column_info');
        $builder->set_input(new Custom('column_info', 'column_info', $this->items));

        return $builder->build();
    }

}
