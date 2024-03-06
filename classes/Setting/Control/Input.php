<?php

declare(strict_types=1);

namespace AC\Setting\Control;

use AC\Setting\AttributeCollection;

class Input implements Component
{

    private $name;

    private $type;

    private $value;

    private $placeholder;

    private $attributes;

    public function __construct(
        string $name,
        string $type,
        $value = null,
        string $placeholder = null,
        AttributeCollection $attributes = null
    ) {
        if ($attributes === null) {
            $attributes = new AttributeCollection();
        }

        $this->name = $name;
        $this->type = $type;
        $this->value = $value;
        $this->placeholder = $placeholder;
        $this->attributes = $attributes;
    }

    public function get_name(): string
    {
        return $this->name;
    }

    public function get_type(): string
    {
        return $this->type;
    }

    public function has_value(): bool
    {
        return $this->value !== null;
    }

    public function get_value()
    {
        return $this->value;
    }

    public function has_placeholder(): bool
    {
        return $this->placeholder !== null;
    }

    public function get_placeholder(): ?string
    {
        return $this->placeholder;
    }

    public function get_attributes(): AttributeCollection
    {
        return $this->attributes;
    }

}