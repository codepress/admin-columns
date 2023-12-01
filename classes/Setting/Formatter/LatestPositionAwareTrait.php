<?php

declare(strict_types=1);

namespace AC\Setting\Formatter;

trait LatestPositionAwareTrait
{

    public function get_position(): int
    {
        return PositionAware::LATEST;
    }

}