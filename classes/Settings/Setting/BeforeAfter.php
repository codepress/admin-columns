<?php

class AC_Settings_Setting_BeforeAfter extends AC_Settings_SettingAbstract
	implements AC_Settings_FormatInterface {

	/**
	 * @var string
	 */
	private $before;

	/**
	 * @var string
	 */
	private $after;

	protected function set_properties() {
		$this->properties = array( 'before', 'after' );
	}

	// todo get_before should apply the trim, or should it be done here?
	public function format( $value ) {
		return $this->get_before() . $value . $this->get_after();
	}

	public function view() {
		$section = $settings[] = new AC_Settings_View();
		$section->set( 'label', __( 'Before', 'codepress-admin-columns' ) )
		        ->set( 'description', __( 'This text will appear before the column value.', 'codepress-admin-columns' ) )
		        ->set( 'settings', $this->create_element( 'before' ) );

		$section = $settings[] = new AC_Settings_View();
		$section->set( 'label', __( 'After', 'codepress-admin-columns' ) )
		        ->set( 'description', __( 'This text will appear after the column value.', 'codepress-admin-columns' ) )
		        ->set( 'settings', $this->create_element( 'after' ) );

		$view = new AC_Settings_View();
		$view->set( 'label', __( 'Display Options', 'codepress-admin-columns' ) )
		     ->set( 'settings', $settings );

		return $view;
	}

	/**
	 * @return string
	 */
	public function get_before() {
		return $this->before;
	}

	/**
	 * @param string $before
	 *
	 * @return $this
	 */
	public function set_before( $before ) {
		$this->before = $before;

		return $this;
	}

	/**
	 * @return string
	 */
	public function get_after() {
		return $this->after;
	}

	/**
	 * @param string $after
	 *
	 * @return $this
	 */
	public function set_after( $after ) {
		$this->after = $after;

		return $this;
	}

}