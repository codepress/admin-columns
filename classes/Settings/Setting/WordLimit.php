<?php

class AC_Settings_Setting_WordLimit extends AC_Settings_SettingAbstract {

	/**
	 * @var int
	 */
	private $except_length = 30;

	protected function set_properties() {
		$this->properties = array( 'excerpt_length' );
	}

	public function view() {
		$setting = $this->create_element( 'excerpt_length', 'number' )
		                ->set_attribute( 'min', 0 )
		                ->set_attribute( 'step', 1 );

		$view = new AC_Settings_View();
		$view->set( 'label', __( 'Word Limit', 'codepress-admin-columns' ) )
		     ->set( 'description', __( 'Maximum number of words', 'codepress-admin-columns' ) . '<em>' . __( 'Leave empty for no limit', 'codepress-admin-columns' ) . '</em>' )
		     ->set( 'settings', $setting );

		return $view;
	}

	/**
	 * @return int
	 */
	public function get_except_length() {
		return $this->except_length;
	}

	/**
	 * @param int $except_length
	 *
	 * @return $this
	 */
	public function set_except_length( $except_length ) {
		$this->except_length = $except_length;

		return $this;
	}

}