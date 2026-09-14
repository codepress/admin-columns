<?php

declare(strict_types=1);

namespace AC\Formatter;

use AC\Formatter;
use AC\FormatterCollection;
use AC\Type\Value;
use AC\Type\ValueCollection;

final class Merge implements Formatter
{
    private FormatterCollection $formatters;

    /**
     * @var array<int, array{separator: string, formatters: FormatterCollection}>
     */
    private array $members;

    public function __construct(FormatterCollection $formatters, array $members)
    {
        $this->formatters = $formatters;
        $this->members = $members;
    }

    public function format(Value $value): Value
    {
        $merged = $this->render($this->formatters, $value);

        foreach ($this->members as $member) {
            $part = $this->render($member['formatters'], $value);

            if ('' === $part) {
                continue;
            }

            $merged = '' === $merged
                ? $part
                : $merged . $member['separator'] . $part;
        }

        return $value->with_value($merged);
    }

    private function render(FormatterCollection $formatters, Value $value): string
    {
        if (0 === $formatters->count()) {
            return '';
        }

        $result = (new Aggregate($formatters))->format($value);

        if ($result instanceof ValueCollection) {
            $result = (new Collection\Implode())->format($result);
        }

        return (string)$result;
    }

}
