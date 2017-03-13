<?php

class AC_Helper_String {

	/**
	 * @since 1.3.1
	 */
	public function shorten_url( $url ) {
		return $url ? '<a title="' . esc_attr( $url ) . '" href="' . esc_attr( $url ) . '">' . esc_html( url_shorten( $url ) ) . '</a>' : false;
	}

	/**
	 * @since 1.3
	 */
	public function strip_trim( $string ) {
		return trim( strip_tags( $string ) );
	}

	/**
	 * Count the number of words in a string (multibyte-compatible)
	 *
	 * @since NEWVERSION
	 *
	 * @param $string
	 *
	 * @return int Number of words
	 */
	public function word_count( $string ) {
		$string = $this->strip_trim( $string );

		$patterns = array(
			'strip' => '/<[a-zA-Z\/][^<>]*>/',
			'clean' => '/[0-9.(),;:!?%#$¿\'"_+=\\/-]+/',
			'w'     => '/\S\s+/',
			'c'     => '/\S/',
		);

		$string = preg_replace( $patterns['strip'], ' ', $string );
		$string = preg_replace( '/&nbsp;|&#160;/i', ' ', $string );
		$string = preg_replace( $patterns['clean'], '', $string );

		if ( ! strlen( preg_replace( '/\s/', '', $string ) ) ) {
			return 0;
		}

		return preg_match_all( $patterns['w'], $string, $matches ) + 1;
	}

	/**
	 * @see   wp_trim_words();
	 *
	 * @since NEWVERSION
	 *
	 * @return string
	 */
	public function trim_words( $string = '', $num_words = 30, $more = null ) {
		return $string ? wp_trim_words( $string, $num_words, $more ) : false;
	}

	/**
	 * @param string $string
	 * @param int    $limit
	 *
	 * @return string
	 */
	public function trim_characters( $string, $limit = 10 ) {
		return is_numeric( $limit ) && 0 < $limit && strlen( $string ) > $limit ? substr( $string, 0, $limit ) . __( '&hellip;' ) : $string;
	}

	/**
	 * Formats a valid hex color to a 6 digit string, optionally prefixed with a #
	 *
	 * Example: #FF0 will be fff000 based on the $prefix parameter
	 *
	 * @param string $hex    Valid hex color
	 * @param bool   $prefix Prefix with a # or not
	 *
	 * @return string
	 */
	protected function hex_format( $hex, $prefix = false ) {
		$hex = ltrim( $hex, '#' );

		if ( strlen( $hex ) == 3 ) {
			$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
		}

		if ( $prefix ) {
			$hex = '#' . $hex;
		}

		return strtolower( $hex );
	}

	/**
	 * Get RGB values from a hex color string
	 *
	 * @since NEWVERSION
	 *
	 * @param string $hex Valid hex color
	 *
	 * @return array
	 */
	public function hex_to_rgb( $hex ) {
		$hex = $this->hex_format( $hex );

		return sscanf( $hex, '%2x%2x%2x' );
	}

	/**
	 * Get contrasting hex color based on given hex color
	 *
	 * @since NEWVERSION
	 *
	 * @param string $hex Valid hex color
	 *
	 * @return string
	 */
	public function hex_get_contrast( $hex ) {
		$rgb = $this->hex_to_rgb( $hex );
		$contrast = ( $rgb[0] * 0.299 + $rgb[1] * 0.587 + $rgb[2] * 0.114 ) < 186 ? 'fff' : '333';

		return $this->hex_format( $contrast, true );
	}

	/**
	 * @since 1.2.0
	 *
	 * @param string $url
	 *
	 * @return bool
	 */
	public function is_image( $url ) {
		return $url && is_string( $url ) ? in_array( strrchr( $url, '.' ), array( '.jpg', '.jpeg', '.gif', '.png', '.bmp' ) ) : false;
	}

	/**
	 * @since NEWVERSION
	 *
	 * @param string $string
	 *
	 * @return array
	 */
	public function comma_separated_to_array( $string ) {
		$array = array();
		if ( is_scalar( $string ) ) {
			if ( strpos( $string, ',' ) !== false ) {
				$array = array_filter( explode( ',', ac_helper()->string->strip_trim( str_replace( ' ', '', $string ) ) ) );
			} else {
				$array = array( $string );
			}
		} else if ( is_array( $string ) ) {
			$array = $string;
		}

		return $array;
	}

	/**
	 * @since NEWVERSION
	 *
	 * @param string $string
	 *
	 * @return array
	 */
	public function string_to_array_integers( $string ) {
		$values = $this->comma_separated_to_array( $string );

		foreach ( $values as $k => $value ) {
			if ( ! is_numeric( $value ) ) {
				unset( $values[ $k ] );
			}
		}

		return $values;
	}

	/**
	 * @since NEWVERSION
	 *
	 * @param string $hex Color Hex Code
	 */
	public function get_color_block( $hex ) {
		return $hex ? '<div class="cpac-color"><span style="background-color:' . esc_attr( $hex ) . ';color:' . esc_attr( $this->hex_get_contrast( $hex ) ) . '">' . esc_html( $hex ) . '</span></div>' : false;
	}

	/**
	 * @return bool
	 */
	public function is_valid_url( $url ) {
		return filter_var( $url, FILTER_VALIDATE_URL ) || preg_match( '/[^\w.-]/', $url );
	}

	/**
	 * @param $content
	 *
	 * @return int Words per minute
	 */
	public function get_estimated_reading_time_in_seconds( $string, $words_per_minute = 200 ) {
		$word_count = $this->word_count( $string );

		return $word_count && $words_per_minute ? (int) floor( ( $word_count / $words_per_minute ) * 60 ) : 0;
	}

	/**
	 * @since NEWVERSION
	 *
	 * @param int $seconds
	 *
	 * @return string
	 */
	public function convert_seconds_to_human_readable_time( $seconds ) {
		$time = false;

		if ( $seconds ) {

			$minutes = floor( $seconds / 60 );
			$seconds = floor( $seconds % 60 );

			$time = $minutes;
			if ( $minutes && $seconds < 10 ) {
				$seconds = '0' . $seconds;
			}
			if ( '00' != $seconds ) {
				$time .= ':' . $seconds;
			}
			if ( $minutes < 1 ) {
				$time = $seconds . ' ' . _n( 'second', 'seconds', $seconds, 'codepress-admin-columns' );
			} else {
				$time .= ' ' . _n( 'minute', 'minutes', $minutes, 'codepress-admin-columns' );
			}
		}

		return $time;
	}

	/**
	 * @return string Display empty value
	 */
	public function get_empty_char() {
		return '&ndash;';
	}

	/**
	 * @param string $string
	 *
	 * @return bool
	 */
	public function contains_html_only( $string ) {
		return strlen( $string ) !== strlen( strip_tags( $string ) );
	}

	/**
	 * @param string $value
	 *
	 * @return bool
	 */
	public function is_empty( $value ) {
		return ! $this->is_not_empty( $value );
	}

	/**
	 * @param string $value
	 *
	 * @return bool
	 */
	public function is_not_empty( $value ) {
		return $value || 0 === $value;
	}

	/**
	 * Return an array into a comma separated sentence. For example [minute, hours, days] becomes: "minute, hours or days".
	 *
	 * @param array $words
	 *
	 * @return string
	 */
	public function enumeration_list( $words, $compound = 'or' ) {
		if ( empty( $words ) || ! is_array( $words ) ) {
			return false;
		}

		if ( 'or' === $compound ) {
			$compound = __( ' or ', 'codepress-admin-columns' );
		} else {
			$compound = __( ' and ', 'codepress-admin-columns' );
		}

		$last = end( $words );
		$delimiter = ', ';

		return str_replace( $delimiter . $last, $compound . $last, implode( $delimiter, $words ) );
	}

}