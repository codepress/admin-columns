<?php

declare(strict_types=1);

namespace AC\Expression;

use AC\Expression\Exception\OperatorNotFoundException;

trait OperatorsTrait
{

    protected string $operator;

    abstract protected function get_operators(): array;

    protected function validate_operator(): void
    {
        if ( ! in_array($this->operator, $this->get_operators(), true)) {
            throw new OperatorNotFoundException($this->operator);
        }
    }

}