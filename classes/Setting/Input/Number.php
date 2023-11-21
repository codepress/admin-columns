<?php

declare(strict_types=1);

namespace AC\Setting\Input;

final class Number extends Single
{

    private $min;

    private $max;

    private $step;

    public function __construct(string $default = null, string $min = null, string $max = null, string $step = '1')
    {
        parent::__construct($default);

        $this->min = $min;
        $this->max = $max;
        $this->step = $step;
    }

    public function get_type(): string
    {
        return 'number';
    }

    public function get_min(): ?string
    {
        return $this->min;
    }

    public function get_max(): ?string
    {
        return $this->max;
    }

    public function get_step(): string
    {
        return $this->step;
    }
    
}