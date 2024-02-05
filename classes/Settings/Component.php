<?php

declare(strict_types=1);

namespace AC\Settings;

use AC;
use AC\Setting\Component\AttributeCollection;
use AC\Setting\Component\Type\Attribute;

class Component implements AC\Setting\Component
{

    protected $label;

    protected $description;

    protected $input;

    protected $conditions;

    private $type;

    public function __construct(
        string $type,
        string $label,
        string $description = null
    ) {
        $this->type = $type;
        $this->label = $label;
        $this->description = $description;
    }

    public function get_type(): string
    {
        return $this->type;
    }

    public function get_attributes(): AttributeCollection
    {
        return new AttributeCollection([
            new Attribute('label', $this->label),
            new Attribute('description', $this->description),
        ]);
    }

}