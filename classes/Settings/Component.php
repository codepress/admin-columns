<?php

declare(strict_types=1);

namespace AC\Settings;

use AC;
use AC\Setting\Component\AttributeCollection;
use AC\Setting\Component\Type\Attribute;

class Component implements AC\Setting\Component
{

    private $type;

    private $attributes;

    private $label;

    public function __construct(
        string $type,
        string $label,
        string $description = null,
        AttributeCollection $attributes = null
    ) {
        if (null === $attributes) {
            $attributes = new AttributeCollection();
        }

        $attributes->add(new Attribute('label', $label));

        if ($description) {
            $attributes->add(new Attribute('description', $description));
        }

        $this->type = $type;
        $this->attributes = $attributes;
        $this->label = $label;
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

}