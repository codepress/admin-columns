<?php

class AC_Settings_Setting_WordsPerMinute extends AC_Settings_SettingAbstract {

	/**
	 * @var int
	 */
	private $words_per_minute = 200;


	protected function set_managed_options() {
		$this->managed_options = array( 'words_per_minute' );
	}

	public function view() {
		$word_limit = $this->create_element( 'words_per_minute', 'number' )
		                   ->set_attribute( 'min', 0 )
		                   ->set_attribute( 'step', 1 )
		                   ->set_attribute( 'placeholder', $this->get_words_per_minute() );

		$view = new AC_Settings_View();
		$view->set( 'label', __( 'Words per minute', 'codepress-admin-columns' ) )
		     ->set( 'tooltip', __( 'Estimated reading time in words per minute.', 'codepress-admin-columns' ) . ' ' . sprintf( __( 'By default: %s', 'codepress-admin-columns' ), $this->get_words_per_minute() ) )
		     ->set( 'setting', $word_limit );

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

}