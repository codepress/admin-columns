<?php

declare(strict_types=1);

namespace AC\Helper\Select\Generic;

interface GroupFormatter
{

    public function format(string $value): string;

}