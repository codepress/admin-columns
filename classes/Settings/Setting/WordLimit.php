<?php

class AC_Settings_Setting_WordLimit extends AC_Settings_SettingAbstract {

	/**
	 * @var int
	 */
	private $excerpt_length = 30;

	protected function set_id() {
		$this->id = 'word_limit';
	}

	protected function set_managed_options() {
		$this->managed_options = array( 'excerpt_length' );
	}

	public function view() {
		$word_limit = $this->create_element( 'excerpt_length', 'number' )
		                   ->set_attribute( 'min', 0 )
		                   ->set_attribute( 'step', 1 );

		$view = new AC_Settings_View();
		$view->set( 'label', __( 'Word Limit', 'codepress-admin-columns' ) )
		     ->set( 'description', __( 'Maximum number of words', 'codepress-admin-columns' ) . '<em>' . __( 'Leave empty for no limit', 'codepress-admin-columns' ) . '</em>' )
		     ->set( 'setting', $word_limit );

		return $view;
	}

	/**
	 * @return int
	 */
	public function get_excerpt_length() {
		return $this->excerpt_length;
	}

	/**
	 * @param int $excerpt_length
	 *
	 * @return $this
	 */
	public function set_excerpt_length( $excerpt_length ) {
		$this->excerpt_length = $excerpt_length;

		return $this;
	}

}