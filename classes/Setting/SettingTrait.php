<?php

declare(strict_types=1);

namespace AC\Setting;

use ACP\Expression\NullSpecification;
use ACP\Expression\Specification;

trait SettingTrait
{

    protected $type = 'default';

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