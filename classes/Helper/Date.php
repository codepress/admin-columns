<?php

namespace AC\Helper;

use DateTimeZone;

class Date extends Creatable
{

    public function get_date_format(): string
    {
        return (string)get_option('date_format') ?: 'F j, Y';
    }

    public function get_time_format(): string
    {
        return (string)get_option('time_format') ?: 'H:i';
    }

    public function get_date_time_format(): string
    {
        return sprintf(
            '%s %s',
            $this->get_date_format(),
            $this->get_time_format()
        );
    }

    /**
     * @depecated 7.0.11
     */
    public function strtotime($date): ?int
    {
        _deprecated_function(__METHOD__, '7.0.11', 'strtotime()');

        return strtotime($date) ?: null;
    }

    /**
     * @depecated 7.0.11
     */
    public function time(string $date, ?string $format = null): ?string
    {
        _deprecated_function(__METHOD__, '7.0.11', 'wp_date()');

        return wp_date($format ?? $this->get_time_format(), strtotime($date), new DateTimeZone('UTC')) ?: null;
    }

    /**
     * @depecated 7.0.11
     */
    public function date(string $date, ?string $date_format = null): ?string
    {
        _deprecated_function(__METHOD__, '7.0.11', 'wp_date()');

        return wp_date($date_format ?? $this->get_date_format(), strtotime($date), new DateTimeZone('UTC')) ?: null;
    }

    /**
     * @depecated 7.0.11
     */
    public function date_by_timestamp(int $timestamp, ?string $date_format = null): ?string
    {
        _deprecated_function(__METHOD__, '7.0.11', 'wp_date()');

        return wp_date($date_format ?? $this->get_date_format(), $timestamp, new DateTimeZone('UTC')) ?: null;
    }

    /**
     * @depecated 7.0.11
     */
    public function timezone(): DateTimeZone
    {
        _deprecated_function(__METHOD__, '7.0.11', 'wp_timezone()');

        return wp_timezone();
    }

    /**
     * @depecated 7.0
     */
    public function format_date(string $format, ?int $timestamp = null, ?DateTimeZone $timezone = null): ?string
    {
        _deprecated_function(__METHOD__, '7.0', 'wp_date()');

        return wp_date($format, $timestamp ?? time(), $timezone ?? new DateTimeZone('UTC')) ?: null;
    }

}