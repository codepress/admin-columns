<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Expression\NullSpecification;
use AC\Expression\Specification;

// TODO remove
trait SettingTrait
{

<<<<<<< HEAD
    protected $type = 'default';
=======
    protected $name = '';
>>>>>>> bf39a92dd4a8273b3c8a4ed1eb27b15114e9f4a2

    protected $label = '';

    protected $description = '';

    /**
     * @var Component
     */
    protected $input;

    /**
     * @var Specification
     */
    protected $conditions;

    public function get_type() : string
    {
        return $this->type;
    }

    public function get_label(): string
    {
        return $this->label;
    }

    public function get_description(): string
    {
        return $this->description;
    }

    public function get_input(): ?Component
    {
        return $this->input;
    }

    public function get_conditions(): Specification
    {
        return $this->conditions ?? new NullSpecification();
    }

}