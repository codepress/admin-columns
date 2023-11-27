<?php

declare(strict_types=1);

namespace AC\Setting\Input;

final class Number extends Open
{

    private $min;

    private $max;

    private $step;

    public function __construct(
        string $min = null,
        string $max = null,
        string $step = null,
        string $default = null,
        string $placeholder = null,
        string $class = null,
        string $append = null
    ) {
        parent::__construct('number', $default, $placeholder, $class, $append);

        $this->min = $min;
        $this->max = $max;
        $this->step = $step;
    }

    public static function create_single_step(
        int $min = null,
        int $max = null,
        int $default = null,
        string $placeholder = null,
        string $class = null,
        string $append = null
    ): self {
        return new self(
            (string)$min,
            (string)$max, '1',
            (string)$default,
            $placeholder,
            $class,
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