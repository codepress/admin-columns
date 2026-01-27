<?php

declare(strict_types=1);

namespace AC\ColumnRepository;

use AC\Collection\ColumnFactories;
use AC\Column;
use AC\ColumnCollection;
use AC\ColumnRepository;
use AC\Setting\Config;
use AC\Setting\ConfigCollection;
use AC\Storage\Repository\OriginalColumnsRepository;
use AC\TableScreen;

class EncodedData implements ColumnRepository
{

    private ColumnFactories $factories;

    private ConfigCollection $configs;

    private OriginalColumnsRepository $original_columns_repository;

    private TableScreen $table_screen;

    public function __construct(
        ColumnFactories $factories,
        ConfigCollection $configs,
        OriginalColumnsRepository $original_columns_repository,
        TableScreen $table_screen
    ) {
        $this->configs = $configs;
        $this->factories = $factories;
        $this->original_columns_repository = $original_columns_repository;
        $this->table_screen = $table_screen;
    }

    public function find_all(): ColumnCollection
    {
        $columns = new ColumnCollection();

        foreach ($this->configs as $config) {
            $config = $this->modify_config($config);

            $column = $this->find((string)$config->get('type'), $config);

            if ($column) {
                $columns->add($column);
            }
        }

        return $columns;
    }

    private function modify_config(Config $config): Config
    {
        // In some rare cases the stored 'name' can have a mismatch with it's 'type' for original columns
        if ($this->original_columns_repository->find($this->table_screen->get_id(), (string)$config->get('type'))) {
            $data = $config->all();
            $data['name'] = $data['type'];

            return new Config($data);
        }

        return $config;
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