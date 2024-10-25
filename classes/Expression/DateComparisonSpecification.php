<?php

declare(strict_types=1);

namespace AC\Expression;

use AC\Expression\Exception\InvalidDateFormatException;
use DateTimeZone;

class DateComparisonSpecification extends ComparisonSpecification implements TypeSpecification
{

    use DateTrait;
    use TypeTrait;

    /**
     * @throws InvalidDateFormatException
     */
    public function __construct(
        string $operator,
        string $fact,
        ?string $format = null,
        ?DateTimeZone $timezone = null
    ) {
        $this->format = $format;
        $this->timezone = $timezone;
        $this->type = Types::DATE;

        parent::__construct(
            $operator,
            $this->create_date_from_value($fact)->getTimestamp()
        );
    }

    /**
     * @throws InvalidDateFormatException
     */
    public function is_satisfied_by($value): bool
    {
        return parent::is_satisfied_by(
            $this->create_date_from_value((string)$value)->getTimestamp()
        );
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