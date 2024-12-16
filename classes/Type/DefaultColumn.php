<?php

declare(strict_types=1);

namespace AC\Type;

class DefaultColumn
{

    private string $name;

    private bool $sortable;

    private string $label;

    public function __construct(string $name, string $label, bool $sortable = false)
    {
        $this->name = $name;
        $this->sortable = $sortable;
        $this->label = $label;
    }

    public function get_name(): string
    {
        return $this->name;
    }

    public function get_label(): string
    {
        return $this->label;
    }

    public function is_sortable(): bool
    {
        return $this->sortable;
    }

    public function with_sortable(bool $sortable): self
    {
        return new self($this->name, $this->label, $sortable);
    }

}