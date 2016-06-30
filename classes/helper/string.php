<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AC_Helper_String {

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
	 * @see wp_trim_words();
	 *
	 * @since NEWVERSION
	 *
	 * @return string
	 */
	public function trim_words( $text = '', $num_words = 30, $more = null ) {
		return $text ? wp_trim_words( $text, $num_words, $more ) : false;
	}

	/**
	 * Formats a valid hex color to a 6 digit string, optionally prefixed with a #
	 *
	 * Example: #FF0 will be fff000 based on the $prefix parameter
	 *
	 * @param string $hex Valid hex color
	 * @param bool $prefix Prefix with a # or not
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
		return is_string( $url ) ? in_array( strrchr( $url, '.' ), array( '.jpg', '.jpeg', '.gif', '.png', '.bmp' ) ) : false;
	}

}