<?php

class AC_Settings_Column_WordsPerMinute extends AC_Settings_Column
	implements AC_Settings_FormatValueInterface {

	/**
	 * @var int
	 */
	private $words_per_minute;

	protected function define_options() {
		return array(
			'words_per_minute' => 200,
		);
	}

	public function create_view() {
		$setting = $this->create_element( 'number' )
		                ->set_attributes( array(
			                'min'         => 0,
			                'step'        => 1,
			                'placeholder' => $this->get_words_per_minute(),
		                ) );

		$view = new AC_View( array(
			'label'   => __( 'Words per minute', 'codepress-admin-columns' ),
			'tooltip' => __( 'Estimated reading time in words per minute.', 'codepress-admin-columns' ) . ' ' . sprintf( __( 'By default: %s', 'codepress-admin-columns' ), $this->get_words_per_minute() ),
			'setting' => $setting,
		) );

		return $view;
	}

	/**
	 * @return int
	 */
	public function get_words_per_minute() {
		return $this->words_per_minute;
	}

	/**
	 * @param int $words_per_minute
	 *
	 * @return $this
	 */
	public function set_words_per_minute( $words_per_minute ) {
		$this->words_per_minute = $words_per_minute;

		return $this;
	}

	/**
	 * Create a human readable time based on seconds
	 *
	 * @param int $seconds
	 *
	 * @since NEWVERSION
	 * @return string
	 */
	protected function make_human_readable( $seconds ) {
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
	 * Return the seconds required to read this string based on average words per minute
	 *
	 * @param string $content
	 *
	 * @return int
	 */
	protected function get_estimated_reading_time_in_seconds( $string ) {
		$word_count = ac_helper()->string->word_count( $string );
		$words_per_minute = $this->get_words_per_minute();
		$seconds = 0;

		if ( $word_count && $words_per_minute ) {
			$seconds = (int) floor( ( $word_count / $words_per_minute ) * 60 );
		}

		return $seconds;
	}

	/**
	 * Returns estimate reading time in seconds
	 *
	 * @param AC_ValueFormatter $value_formatter
	 *
	 * @return AC_ValueFormatter
	 */
	public function format( AC_ValueFormatter $value_formatter ) {
		$value_formatter->value = $this->make_human_readable( $this->get_estimated_reading_time_in_seconds( $value_formatter->value ) );

		return $value_formatter;
	}

}