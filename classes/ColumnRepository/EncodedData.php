<?php

declare(strict_types=1);

namespace AC\ColumnRepository;

use AC\Collection\ColumnFactories;
use AC\Column;
use AC\ColumnCollection;
use AC\ColumnRepository;
use AC\Setting\Config;
use AC\Setting\ConfigCollection;

class EncodedData implements ColumnRepository
{

    private ColumnFactories $factories;

    private ConfigCollection $configs;

    public function __construct(ColumnFactories $factories, ConfigCollection $configs)
    {
        $this->configs = $configs;
        $this->factories = $factories;
    }

    public function find_all(): ColumnCollection
    {
        $columns = new ColumnCollection();

        foreach ($this->configs as $config) {
            $column = $this->find((string)$config->get('type'), $config);

            if ($column) {
                $columns->add($column);
            }
        }

        return $columns;
    }

    private function find(string $type, Config $config): ?Column
    {
        foreach ($this->factories as $factory) {
            if ($type === $factory->get_column_type()) {
                return $factory->create($config);
            }
        }

        return null;
    }

}