<?php

declare(strict_types=1);

namespace AC\Formatter;

trait LatestPositionAwareTrait
{

    public function get_position(): int
    {
        return PositionAware::LATEST;
    }

}