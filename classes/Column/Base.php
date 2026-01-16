<?php

declare(strict_types=1);

namespace AC\Column;

use AC\Column;
use AC\FormatterCollection;
use AC\Setting\Component;
use AC\Setting\ComponentCollection;
use AC\Type\ColumnId;

class Base implements Column
{

    protected string $type;

    protected string $label;

    protected ComponentCollection $settings;

    private ColumnId $id;

    private Context $context;

    private FormatterCollection $formatters;

    protected string $group;

    public function __construct(
        string $type,
        string $label,
        ComponentCollection $settings,
        ColumnId $id,
        Context $context,
        ?FormatterCollection $formatters = null,
        ?string $group = null
    ) {
        $this->type = $type;
        $this->label = $label;
        $this->settings = $settings;
        $this->context = $context;
        $this->id = $id;
        $this->formatters = $formatters ?? new FormatterCollection();
        $this->group = $group ?? 'custom';
    }

    public function get_type(): string
    {
        return $this->type;
    }

    public function get_id(): ColumnId
    {
        return $this->id;
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

    public function get_context(): Context
    {
        return $this->context;
    }

}