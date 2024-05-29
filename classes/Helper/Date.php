<?php

namespace AC\Helper;

use DateTimeZone;

class Date
{

    /**
     * @param string|int $date
     */
    public function strtotime($date): ?int
    {
        if ( ! is_scalar($date) ||
             empty($date) ||
             in_array($date, ['0000-00-00 00:00:00', '0000-00-00', '00:00:00'], true)
        ) {
            return null;
        }

        // some plugins store dates in a jquery timestamp format, format is in ms since The Epoch.
        // See http://api.jqueryui.com/datepicker/#utility-formatDate
        if (is_numeric($date)) {
            $length = strlen(trim($date));

            // Dates before / around September 8th, 2001 are saved as 9 numbers * 1000 resulting in 12 numbers to store the time.
            // Dates after September 8th are saved as 10 numbers * 1000, resulting in 13 numbers.
            // For example the ACF Date and Time Picker uses this format.
            // credits: Ben C
            if (12 === $length || 13 === $length) {
                return (int)round($date / 1000); // remove the ms
            }

            // Date format: yyyymmdd ( often used by ACF ) must start with 19xx or 20xx and is 8 long
            // in theory a numeric string of 8 can also be a unix timestamp; no conversion would be needed
            if (8 === $length && (strpos($date, '20') === 0 || strpos($date, '19') === 0)) {
                return strtotime($date) ?: null;
            }

            return (int)$date;
        }

        return strtotime($date) ?: null;
    }

    public function date(string $date, string $date_format = null): ?string
    {
        return $this->date_by_timestamp(
            $this->strtotime($date),
            $date_format
        );
    }

    public function date_by_timestamp(int $timestamp, string $date_format = null): ?string
    {
        if ( ! $date_format) {
            $date_format = 'wp_date';
        }

        switch ($date_format) {
            case 'wp_date' :
                $date_format = (string)get_option('date_format');

                break;
            case 'wp_date_time' :
                $date_format = get_option('date_format') . ' ' . get_option('time_format');

                break;
        }

        // Fallback in case the date format from General Settings is empty
        if ( ! $date_format) {
            $date_format = 'F j, Y';
        }

        return wp_date($date_format, $timestamp) ?: null;
    }

    // TODO remove
    public function format_date(string $format, int $timestamp = null, DateTimeZone $timezone = null)
    {
        return wp_date($format, $timestamp, $timezone);
    }

    public function timezone(): ?DateTimeZone
    {
        return wp_timezone();
    }

    public function time(string $date, string $format = ''): ?string
    {
        $timestamp = ac_helper()->date->strtotime($date);

        if ( ! $format) {
            $format = (string)get_option('time_format');
        }

        if ( ! $timestamp) {
            return null;
        }

        return wp_date($format, $timestamp) ?: null;
    }

}