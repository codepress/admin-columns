<?php

declare(strict_types=1);

namespace AC\Column;

use AC\Column;
use AC\Setting;
use AC\Setting\ComponentCollection;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;

abstract class CustomColumnFactory implements ColumnFactory
{

    private Setting\ComponentFactory\Name $name;

    private Setting\ComponentFactory\Label $label;

    private Setting\ComponentFactory\Width $width;

    public function __construct(
        Setting\ComponentFactory\Name $name,
        Setting\ComponentFactory\Label $label,
        Setting\ComponentFactory\Width $width
    ) {
        $this->name = $name;
        $this->label = $label;
        $this->width = $width;
    }

    protected function get_formatters(Config $config, ComponentCollection $components): FormatterCollection
    {
        return $this->get_setting_formatters($components);
    }

    protected function get_setting_formatters(ComponentCollection $components): FormatterCollection
    {
        $formatters = new FormatterCollection();

        foreach ($components as $component) {
            foreach ($component->get_formatters() as $formatter) {
                $formatters->add($formatter);
            }
        }

        return $formatters;
    }

    protected function get_group(): ?string
    {
        return null;
    }

    protected function get_components(Config $config): array
    {
        return [
            $this->name,
            $this->label,
            $this->width,
        ];
    }

    public function create(Config $config): Column
    {
        $components = new ComponentCollection([
            $this->name->create($config),
            $this->label->create($config),
            $this->width->create($config),
        ]);

        foreach ($this->get_components($config) as $component) {
            $components->add($component->create($config));
        }

        return new Base(
            $this->get_column_type(),
            $this->get_label(),
            $components,
            $this->get_formatters($config, $components),
            $this->get_group()
        );
    }

}