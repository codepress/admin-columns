<?php

declare(strict_types=1);

namespace AC\Type;

use InvalidArgumentException;

class OriginalColumn
{

    private string $name;

    private string $label;

    private bool $sortable;

    public function __construct(string $name, string $label = '', bool $sortable = false)
    {
        $this->name = $name;
        $this->sortable = $sortable;
        $this->label = $label;

        if ( ! self::validate($name, $label)) {
            throw new InvalidArgumentException('Invalid column name or label');
        }
    }

    public static function validate($name, $label): bool
    {
        return $name && is_scalar($name) && is_scalar($label);
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

    public function set_sortable(bool $sortable): void
    {
        $this->sortable = $sortable;
    }

}