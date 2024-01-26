<?php

declare(strict_types=1);

namespace AC\Expression;

trait TypeTrait
{

    abstract protected function get_type(): string;

}