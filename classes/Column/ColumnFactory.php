<?php

namespace AC\Column;

use AC\Column;
use AC\FormatterCollection;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Type\ColumnId;

abstract class ColumnFactory
{

    abstract public function create(Config $config): Column;

    abstract public function get_column_type(): string;

    abstract public function get_label(): string;

    protected function resolve_id(Config $config): ColumnId
    {
        $id = (string)$config->get('name');

        if (ColumnId::is_valid_id($id)) {
            return new ColumnId($id);
        }

        return (new ColumnIdGenerator())->generate();
    }

    protected function get_settings(Config $config): ComponentCollection
    {
        return new ComponentCollection();
    }

    protected function get_context(Config $config): Context
    {
        return new Context($config, $this->get_label());
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        return $this->get_formatters_from_settings(
            $this->get_settings($config)
        );
    }

    private function get_formatters_from_settings(ComponentCollection $settings): FormatterCollection
    {
        $formatters = new FormatterCollection();

        foreach ($settings as $setting) {
            foreach ($setting->get_formatters() as $formatter) {
                $formatters->add($formatter);
            }
        }

        return $formatters;
    }

}