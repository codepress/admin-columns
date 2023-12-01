<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Exception\MissingFormatterException;
use AC\Formatter;

trait FormatterByValueTrait
{

    public function has_formatter($value): bool
    {
        return $this->get_formatter_by_value($value) instanceof Formatter;
    }

    public function get_formatter($value): Formatter
    {
        if ( ! $this->has_formatter($value)) {
            throw new MissingFormatterException();
        }

        return $this->get_formatter_by_value($value);
    }

    abstract protected function get_formatter_by_value($value): ?Formatter;

}