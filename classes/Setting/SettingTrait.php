<?php

declare(strict_types=1);

namespace AC\Setting;

// TODO move to core
use ACP\Expression\NullSpecification;
use ACP\Expression\Specification;

trait SettingTrait
{

    protected $name = '';

    protected $label = '';

    protected $description = '';

    /**
     * @var Input
     */
    protected $input;

    /**
     * @var Specification
     */
    protected $conditions;

    public function get_name(): string
    {
        return $this->name;
    }

    public function get_label(): string
    {
        return $this->label;
    }

    public function get_description(): string
    {
        return $this->description;
    }

    public function get_input(): ?Input
    {
        return $this->input;
    }

    public function get_conditions(): Specification
    {
        return $this->conditions ?? new NullSpecification();
    }

}