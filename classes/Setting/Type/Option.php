<?php

declare(strict_types=1);

namespace AC\Setting\Type;

final class Option
{

    protected $label;

    private $value;

    private $group;

    public function __construct(string $label, string $value, string $group = null)
    {
        $this->label = $label;
        $this->value = $value;
        $this->group = $group;
    }

    public static function from_value(string $value, string $group = null): self
    {
        return new self($value, $value, $group);
    }

    public function get_label(): string
    {
        return $this->label;
    }

    public function get_value(): string
    {
        return $this->value;
    }

    public function has_group(): bool
    {
        return $this->group !== null;
    }

    public function get_group(): ?string
    {
        return $this->group;
    }

}