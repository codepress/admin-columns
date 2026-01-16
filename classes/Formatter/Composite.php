<?php

declare(strict_types=1);

namespace AC\Formatter;

use AC\Exception\ValueNotFoundException;
use AC\Formatter;
use AC\Type\Value;

class Composite implements Formatter
{

    /**
     * @var Formatter[] $formatters
     */
    private array $formatters;

    private string $separator;

    public function __construct(array $formatters, string $separator = ' ')
    {
        $this->formatters = $formatters;
        $this->separator = $separator;
    }

    public function format(Value $value): Value
    {
        $values = [];

        foreach ($this->formatters as $formatter) {
            try {
                $result = (string)$formatter->format($value);
            } catch (ValueNotFoundException $e) {
                continue;
            }

            if ('' === $result) {
                continue;
            }

            $values[] = (string)$result;
        }

        if ( ! $values) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        return $value->with_value(
            trim(implode($this->separator, $values))
        );
    }

}