<?php

declare(strict_types=1);

namespace AC\Setting;

use AC\Exception\MissingFormatterException;
use AC\Formatter;

trait FormatterTrait
{

    /**
     * @var Formatter
     */
    protected $formatter;

    public function has_formatter($value): bool
    {
        return true;
    }

    public function get_formatter($value): Formatter
    {
        if ( ! $this->has_formatter($value)) {
            throw new MissingFormatterException();
        }

        return $this->formatter;
    }

}