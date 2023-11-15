<?php

declare(strict_types=1);

namespace AC\Helper\Select\Generic;

interface LabelFormatter
{

    public function format_label(string $value): string;

}