<?php

declare(strict_types=1);

namespace AC\Setting\Component;

class Input
{

    private $name;

    private $type;

    private $default;

    private $placeholder;

    private $attributes;

    public function __construct(
        string $name,
        string $type,
        $default = null,
        string $placeholder = null,
        AttributeCollection $attributes = null
    ) {
        if ($attributes === null) {
            $attributes = new AttributeCollection();
        }

        $this->name = $name;
        $this->type = $type;
        $this->default = $default;
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

    public function has_default(): bool
    {
        return $this->default !== null;
    }

    public function get_default()
    {
        return $this->default;
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