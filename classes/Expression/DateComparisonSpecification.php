<?php

declare(strict_types=1);

namespace AC\Expression;

use AC\Expression;
use DateTimeZone;

class DateComparisonSpecification extends Expression\DateSpecification
{

    use ComparisonTrait {
        get_rules as get_comparison_rules;
    }

    /**
     * @throws Exception\InvalidDateFormatException
     */
    public function __construct(
        string $fact,
        string $operator,
        string $format = null,
        DateTimeZone $timezone = null
    ) {
        parent::__construct($format, $timezone);

        $this->fact = (int)$this->create_date_from_value($fact)->format('U');
        $this->operator = $operator;

        $this->validate_operator();
    }

    /**
     * @throws Exception\InvalidDateFormatException
     */
    public function is_satisfied_by(string $value): bool
    {
        return $this->compare(
            $this->operator,
            (int)$this->create_date_from_value($value)->format('U')
        );
    }

    protected function get_type(): string
    {
        return Types::DATE;
    }

    public function get_rules(): array
    {
        return array_merge(
            $this->get_comparison_rules(),
            parent::get_rules()
        );
    }

}