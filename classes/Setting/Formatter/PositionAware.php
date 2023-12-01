<?php

declare(strict_types=1);

namespace AC\Setting\Formatter;

interface PositionAware
{

    public const LATEST = 25;

    public function get_position(): int;

}