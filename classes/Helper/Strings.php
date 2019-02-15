<?php

namespace AC\Helper;

class Strings {

	/**
	 * @since 1.3.1
	 *
	 * @param $url
	 *
	 * @return bool|false|string
	 */
	public function shorten_url( $url ) {
		if ( ! $url ) {
			return false;
		}

		return ac_helper()->html->link( $url, url_shorten( $url ), array( 'title' => $url ) );
	}

	/**
	 * @since 1.3
	 *
	 * @param $string
	 *
	 * @return string
	 */
	public function strip_trim( $string ) {
		return trim( strip_tags( $string ) );
	}

	/**
	 * Count the number of words in a string (multibyte-compatible)
	 * @since 3.0
	 *
	 * @param $string
	 *
	 * @return int Number of words
	 */
	public function word_count( $string ) {
		if ( empty( $string ) ) {
			return false;
		}

		$string = $this->strip_trim( $string );

		if ( empty( $string ) ) {
			return false;
		}

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
	 * @since 3.0
	 *
	 * @param string $string
	 * @param int    $num_words
	 * @param null   $more
	 *
	 * @return false|string
	 */
	public function trim_words( $string = '', $num_words = 30, $more = null ) {
		if ( ! $string ) {
			return false;
		}

		if ( ! $num_words ) {
			return $string;
		}

		return wp_trim_words( $string, $num_words, $more );
	}

	/**
	 * Trims a string and strips tags if there is any HTML
	 *
	 * @param string $string
	 * @param int    $limit
	 * @param null   $trail
	 *
	 * @return string
	 */
	public function trim_characters( $string, $limit = 10, $trail = null ) {
		$limit = absint( $limit );

		if ( 1 > $limit ) {
			return $string;
		}

		$string = wp_strip_all_tags( $string );

		if ( mb_strlen( $string ) <= $limit ) {
			return $string;
		}

		if ( null === $trail ) {
			$trail = __( '&hellip;' );
		}

		return mb_substr( $string, 0, $limit ) . $trail;
	}

	/**
	 * Formats a valid hex color to a 6 digit string, optionally prefixed with a #
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
	 * @since 3.0
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
	 * @since 3.0
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
		if ( ! $url || ! is_string( $url ) ) {
			return false;
		}

		$ext = strtolower( pathinfo( strtok( $url, '?' ), PATHINFO_EXTENSION ) );

		return in_array( $ext, array( 'jpg', 'jpeg', 'gif', 'png', 'bmp' ) );
	}

	/**
	 * @since 3.0
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
	 * @since 3.0
	 *
	 * @param string $string
	 *
	 * @return array
	 */
	public function string_to_array_integers( $string ) {
		$integers = array();

		foreach ( $this->comma_separated_to_array( $string ) as $k => $value ) {
			if ( is_numeric( trim( $value ) ) ) {
				$integers[] = $value;
			}
		}

		return $integers;
	}

	/**
	 * @since 3.0
	 *
	 * @param string $hex Color Hex Code
	 *
	 * @return string
	 */
	public function get_color_block( $hex ) {
		if ( ! $hex ) {
			return false;
		}

		return '<div class="cpac-color"><span style="background-color:' . esc_attr( $hex ) . ';color:' . esc_attr( $this->hex_get_contrast( $hex ) ) . '">' . esc_html( $hex ) . '</span></div>';
	}

	/**
	 * @param $url
	 *
	 * @return bool
	 */
	public function is_valid_url( $url ) {
		return filter_var( $url, FILTER_VALIDATE_URL ) || preg_match( '/[^\w.-]/', $url );
	}

	/**
	 * @return string Display empty value
	 */
	public function get_empty_char() {
		_deprecated_function( __METHOD__, '3.0', 'AC\Column::get_empty_char' );

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
	 * @param array  $words
	 * @param string $compound
	 *
	 * @return string
	 */
	public function enumeration_list( $words, $compound = 'or' ) {
		if ( empty( $words ) || ! is_array( $words ) ) {
			return false;
		}

		if ( 'and' === $compound ) {
			return wp_sprintf( '%l', $words );
		}

		if ( 'or' === $compound ) {
			$compound = __( ' or ', 'codepress-admin-columns' );
		}

		$last = end( $words );
		$delimiter = ', ';

		return str_replace( $delimiter . $last, $compound . $last, implode( $delimiter, $words ) );
	}

}