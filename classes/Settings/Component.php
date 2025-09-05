<?php

declare(strict_types=1);

namespace AC\Settings;

use AC;
use AC\Setting\AttributeCollection;
use BadMethodCallException;

class Component extends AC\Setting\Component
{

    private string $type;

    private string $label;

    private string $description;

    private AttributeCollection $attributes;

    public function ____construct(
        string $type,
        string $label,
        ?string $description = null,
        ?AttributeCollection $attributes = null
    ) {
        if (null === $attributes) {
            $attributes = new AttributeCollection();
        }

        $this->type = $type;
        $this->label = $label;
        $this->description = $description;
        $this->attributes = $attributes;
    }

    public function get_type(): string
    {
        return $this->type;
    }

    public function get_attributes(): AttributeCollection
    {
        return clone $this->attributes;
    }

    public function get_label(): string
    {
        return $this->label;
    }

    public function has_description(): bool
    {
        return $this->has_description() !== null;
    }

    public function get_description(): string
    {
        if ( ! $this->has_description()) {
            throw new BadMethodCallException();
        }

        return $this->description;
    }

}