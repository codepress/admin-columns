<?php

declare(strict_types=1);

namespace AC\Setting\Control\Input;

use AC\Setting\AttributeCollection;

final class Number extends Open
{

    private ?string $min;

    private ?string $max;

    private ?string $step;

    public function __construct(
        string $name,
        string $min = null,
        string $max = null,
        string $step = null,
        string $default = null,
        string $placeholder = null,
        AttributeCollection $attributes = null,
        string $append = null
    ) {
        parent::__construct($name, 'number', $default, $placeholder, $attributes, $append);

        $this->min = $min;
        $this->max = $max;
        $this->step = $step;
    }

    public static function create_single_step(
        string $name,
        int $min = null,
        int $max = null,
        int $default = null,
        string $placeholder = null,
        AttributeCollection $attributes = null,
        string $append = null
    ): self {
        return new self(
            $name,
            (string)$min,
            (string)$max, '1',
            (string)$default,
            $placeholder,
            $attributes,
            $append
        );
    }

    public function get_type(): string
    {
        return 'number';
    }

    public function has_min(): bool
    {
        return $this->min !== null;
    }

    public function get_min(): ?string
    {
        return $this->min;
    }

    public function has_max(): bool
    {
        return $this->max !== null;
    }

    public function get_max(): ?string
    {
        return $this->max;
    }

    public function has_step(): bool
    {
        return $this->step !== null;
    }

    public function get_step(): ?string
    {
        return $this->step;
    }

}