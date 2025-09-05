<?php

declare(strict_types=1);

namespace AC\Type;

class ColumnParent
{

    private $label;

    private $group;

    public function __construct(string $label, ?string $group = null)
    {
        $this->label = $label;
        $this->group = $group;
    }

    public function get_label(): string
    {
        return $this->label;
    }

    public function get_group(): ?string
    {
        return $this->group;
    }

}