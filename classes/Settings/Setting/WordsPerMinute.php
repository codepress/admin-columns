<?php

class AC_Settings_Setting_WordsPerMinute extends AC_Settings_Setting {

	/**
	 * @var int
	 */
	private $words_per_minute = 200;

	protected function set_managed_options() {
		$this->managed_options = array( 'words_per_minute' );
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
	 * @param string $string
	 *
	 * @return int Seconds
	 */
	public function format( $string ) {
		return ac_helper()->string->get_estimated_reading_time_in_seconds( $string, $this->get_words_per_minute() );
	}

}