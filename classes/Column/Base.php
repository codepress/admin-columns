<?php

declare(strict_types=1);

namespace AC\Column;

use AC\Column;
use AC\Setting\Component;
use AC\Setting\ComponentCollection;
use AC\Setting\FormatterCollection;
use AC\Type\ColumnId;

class Base implements Column
{

    protected string $type;

    protected string $label;

    protected ComponentCollection $settings;

    protected string $group;

    private FormatterCollection $formatters;

    public function __construct(
        string $type,
        string $label,
        ComponentCollection $settings,
        FormatterCollection $formatters = null,
        string $group = null
    ) {
        if ($formatters === null) {
            $formatters = new FormatterCollection();
        }

        if ($group === null) {
            $group = 'custom';
        }

        $this->type = $type;
        $this->label = $label;
        $this->settings = $settings;
        $this->formatters = $formatters;
        $this->group = $group;
    }

    public function get_type(): string
    {
        return $this->type;
    }

    public function get_id(): ColumnId
    {
        // TODO add ColumnId to constructor?
        $id = $this->get_setting('name')
                   ->get_input()
                   ->get_value();

        return ColumnId::is_valid_id($id)
            ? new ColumnId($id)
            : ColumnId::generate();
    }

    public function get_label(): string
    {
        return $this->label;
    }

    public function get_group(): string
    {
        return $this->group;
    }

    public function get_settings(): ComponentCollection
    {
        return $this->settings;
    }

    public function get_formatters(): FormatterCollection
    {
        return $this->formatters;
    }

    public function get_setting(string $name): ?Component
    {
        return $this->settings->find($name);
    }

}