<?php

declare(strict_types=1);

namespace AC\Expression;

trait OperatorTrait
{

    abstract protected function get_operator(): string;

}