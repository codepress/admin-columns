<?php

declare(strict_types=1);

namespace AC\Expression;

use AC\Expression\Exception\InvalidDateFormatException;
use DateTime;
use DateTimeZone;

abstract class DateSpecification implements Specification
{

    use SpecificationTrait;

    public const MYSQL_DATE = 'Y-m-d';
    public const MYSQL_DATE_TIME = 'Y-m-d H:i:s';

    private $format;

    private $timezone;

    public function __construct(string $format = null, DateTimeZone $time_zone = null)
    {
        if (null === $format) {
            $format = self::MYSQL_DATE;
        }
        if (null === $time_zone) {
            $time_zone = wp_timezone();
        }

        $this->format = $format;
        $this->timezone = $time_zone;
    }

    /**
     * @throws InvalidDateFormatException
     */
    protected function create_date_from_value(string $value): DateTime
    {
        $date_time = DateTime::createFromFormat($this->format, $value, $this->timezone);

        if ( ! $date_time) {
            throw new InvalidDateFormatException($value, $this->format);
        }

        if ($this->format === self::MYSQL_DATE) {
            $date_time->setTime(0, 0);
        }

        return $date_time;
    }

    protected function create_modified_date(string $modifier): DateTime
    {
        $date_time = new DateTime($modifier, $this->timezone);

        if ($this->format === self::MYSQL_DATE) {
            $date_time->setTime(0, 0);
        }

        return $date_time;
    }

    protected function get_current_date(): DateTime
    {
        return (new DateTime())->setTimezone($this->timezone);
    }

    public function get_rules(string $value): array
    {
        return [
            'timezone' => $this->timezone->getName(),
            'format'   => $this->format,
        ];
    }

}