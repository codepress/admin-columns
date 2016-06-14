<?php

class AC_Helper_Date {

	// the formatting type?
	public function i18n( $date, $format = false, $format_type = 'date' ) {
		$timestamp = $this->timestamp( $date );

		if ( ! $timestamp ) {
			return false;
		}

		if ( ! $format ) {
			$format = get_option( $format_type . '_format' );
		}

		return date_i18n( $format, $timestamp );
	}

	public function strtotime( $date ) {
		if ( empty( $date ) || in_array( $date, array( '0000-00-00 00:00:00', '0000-00-00', '00:00:00' ) ) ) {
			return false;
		}

		// some plugins store dates in a jquery timestamp format, format is in ms since The Epoch.
		// See http://api.jqueryui.com/datepicker/#utility-formatDate
		// credits: nmarks
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
			// @todo: in theory a numeric string of 8 can also be a unixtimestamp; no conversion would be needed
			if ( 8 === $length && ( strpos( $date, '20' ) === 0 || strpos( $date, '19' ) === 0 ) ) {
				$date = strtotime( $date );
			}
		} else {
			// Parse with strtotime if it's not numeric
			$date = strtotime( $date );
		}

		return $date;
	}

}