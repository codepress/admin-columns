<?php

declare(strict_types=1);

namespace AC\Setting;

use ACP\Expression\Specification;
use BadMethodCallException;

trait SettingTrait
{

    protected $name;

    protected $label = '';

    protected $description = '';

    private $input;

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

    public function get_input(): Input
    {
        return $this->input;
    }

    public function has_conditions(): bool
    {
        return $this->conditions !== null;
    }

    public function get_conditions(): Specification
    {
        if ( ! $this->has_conditions()) {
            throw new BadMethodCallException('No conditions set.');
        }

        return $this->conditions;
    }

}