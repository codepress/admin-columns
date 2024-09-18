<?php

declare(strict_types=1);

namespace AC\Value\Formatter;

use AC\Setting\Formatter;
use AC\Setting\FormatterCollection;
use AC\Table\ProcessFormatters;
use AC\Type\Value;

final class Aggregate implements Formatter
{

    private FormatterCollection $formatters;

    public function __construct(FormatterCollection $formatters)
    {
        $this->formatters = $formatters;
    }

    public function format(Value $value): Value
    {
        return (new ProcessFormatters($this->formatters))->format($value);
    }

}