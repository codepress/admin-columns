<?php

declare(strict_types=1);

namespace AC\Settings;

use AC;
use AC\Expression\NullSpecification;
use AC\Expression\Specification;
use AC\Setting\Component\Input;

class Control extends Component implements AC\Setting\Control
{

    protected $input;

    protected $conditions;

    public function __construct(
        Input $input,
        string $label,
        string $description = null,
        Specification $conditions = null
    ) {
        parent::__construct(
            'row',
            $label,
            $description
        );

        $this->input = $input;
        $this->conditions = $conditions ?? new NullSpecification();
    }

    public function get_input(): Input
    {
        return $this->input;
    }

    public function get_name(): string
    {
        return $this->input->get_name();
    }

    public function get_conditions(): Specification
    {
        return $this->conditions;
    }

}