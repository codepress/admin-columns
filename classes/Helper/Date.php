<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AC_Helper_Date {

	public function strtotime( $date ) {
		if ( empty( $date ) || in_array( $date, array( '0000-00-00 00:00:00', '0000-00-00', '00:00:00' ) ) ) {
			return false;
		}

		// some plugins store dates in a jquery timestamp format, format is in ms since The Epoch.
		// See http://api.jqueryui.com/datepicker/#utility-formatDate
		if ( is_numeric( $date ) ) {
			$length = strlen( trim( $date ) );

			// Dates before / around September 8th, 2001 are saved as 9 numbers * 1000 resulting in 12 numbers to store the time.
			// Dates after September 8th are saved as 10 numbers * 1000, resulting in 13 numbers.
			// For example the ACF Date and Time Picker uses this format.
			// credits: Ben C
			if ( 12 === $length || 13 === $length ) {
				$date = round( $date / 1000 ); // remove the ms
			}

			// Date format: yyyymmdd ( often used by ACF ) must start with 19xx or 20xx and is 8 long
			// @todo: in theory a numeric string of 8 can also be a unix timestamp; no conversion would be needed
			if ( 8 === $length && ( strpos( $date, '20' ) === 0 || strpos( $date, '19' ) === 0 ) ) {
				$date = strtotime( $date );
			}
		}
		else {
			$date = strtotime( $date );
		}

		return $date;
	}

	/**
	 * @since 1.3.1
	 *
	 * @param string $date
	 *
	 * @return string Formatted date
	 */
	public function date( $date, $format = '' ) {
		$timestamp = ac_helper()->date->strtotime( $date );

		// Get date format from the General Settings
		if ( ! $format ) {
			$format = get_option( 'date_format' );
		}

		// Fallback in case the date format from General Settings is empty
		if ( ! $format ) {
			$format = 'F j, Y';
		}

		return $timestamp ? date_i18n( $format, $timestamp ) : false;
	}

	/**
	 * @since 1.3.1
	 *
	 * @param string $date
	 *
	 * @return string Formatted time
	 */
	public function time( $date, $format = '' ) {
		$timestamp = ac_helper()->date->strtotime( $date );
		if ( ! $format ) {
			$format = get_option( 'time_format' );
		}

		return $timestamp ? date_i18n( $format, $timestamp ) : false;
	}

}