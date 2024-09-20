<?php

declare(strict_types=1);

namespace AC\Expression;

use AC\Expression\Exception\InvalidDateFormatException;
use DateTimeZone;

class DateRangeSpecification extends RangeSpecification implements TypeSpecification, DateSpecification
{

    use DateTrait;
    use TypeTrait;

    /**
     * @throws InvalidDateFormatException
     */
    public function __construct(
        string $operator,
        string $a,
        string $b,
        ?string $format = null,
        ?DateTimeZone $timezone = null
    ) {
        parent::__construct(
            $operator,
            DateTimeFactory::create_from_format($a, $format, $timezone)->getTimestamp(),
            DateTimeFactory::create_from_format($b, $format, $timezone)->getTimestamp()
        );

        $this->format = $format;
        $this->timezone = $timezone;
        $this->type = Types::DATE;
    }

    public function is_satisfied_by($value): bool
    {
        return parent::is_satisfied_by((string)$value);
    }

    public function export(): array
    {
        return array_merge(
            parent::export(),
            $this->export_type(),
            $this->export_date()
        );
    }

}