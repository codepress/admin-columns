<?php

declare(strict_types=1);

namespace AC\Helper;

use DateTimeZone;

/**
 * @deprecated 7.0.11
 */
class Date extends Creatable
{
    /**
     * @deprecated 7.0.11
     */
    public function strtotime($date): ?int
    {
        _deprecated_function(__METHOD__, '7.0.11', 'strtotime()');

        return strtotime($date) ?: null;
    }

    /**
     * @deprecated 7.0.11
     */
    public function time(string $date, ?string $format = null): ?string
    {
        _deprecated_function(__METHOD__, '7.0.11', 'wp_date()');

        return wp_date($format ?? WpDateFormat::time(), strtotime($date) ?: null, new DateTimeZone('UTC')) ?: null;
    }

    /**
     * @deprecated 7.0.11
     */
    public function date(string $date, ?string $date_format = null): ?string
    {
        _deprecated_function(__METHOD__, '7.0.11', 'wp_date()');

        return wp_date($date_format ?? WpDateFormat::date(), strtotime($date) ?: null, new DateTimeZone('UTC')) ?: null;
    }

    /**
     * @deprecated 7.0.11
     */
    public function date_by_timestamp(int $timestamp, ?string $date_format = null): ?string
    {
        _deprecated_function(__METHOD__, '7.0.11', 'wp_date()');

        return wp_date($date_format ?? WpDateFormat::date(), $timestamp, new DateTimeZone('UTC')) ?: null;
    }

    /**
     * @deprecated 7.0.11
     */
    public function timezone(): DateTimeZone
    {
        _deprecated_function(__METHOD__, '7.0.11', 'wp_timezone()');

        return wp_timezone();
    }

    /**
     * @deprecated 7.0
     */
    public function format_date(string $format, ?int $timestamp = null, ?DateTimeZone $timezone = null): ?string
    {
        _deprecated_function(__METHOD__, '7.0', 'wp_date()');

        return wp_date($format, $timestamp ?? time(), $timezone ?? new DateTimeZone('UTC')) ?: null;
    }

}
