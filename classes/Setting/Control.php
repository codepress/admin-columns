<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Expression\NullSpecification;
use AC\Expression\Specification;
use AC\Setting\Control\Input;

class Control
{

    private $input;

    private $conditions;

    public function __construct(
        Input $input,
        Specification $conditions = null
    ) {
        $this->input = $input;
        $this->conditions = $conditions;
    }

    public function get_input(): Input
    {
        return $this->input;
    }

    public function get_name(): string
    {
        return $this->input->get_name();
    }

    public function has_conditions(): bool
    {
        return $this->conditions !== null;
    }

    public function get_conditions(): Specification
    {
        if ($this->has_conditions()) {
            return new NullSpecification();
        }

        return $this->conditions;
    }

}