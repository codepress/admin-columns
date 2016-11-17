<?php

class AC_Settings_Setting_BeforeAfter extends AC_Settings_SettingAbstract {

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

	public function render() {
		$view = $this->get_view();
		$view->set( 'label', __( 'Display Options', 'codepress-admin-columns' ) );

		$settings[] = $section = $this->create_view();
		$section->set( 'label', __( 'Before', 'codepress-admin-columns' ) )
		        ->set( 'description', __( 'This text will appear before the column value.', 'codepress-admin-columns' ) )
		        ->set( 'settings', $this->add_element( 'before' ) );

		$settings[] = $section = $this->create_view();
		$section->set( 'label', __( 'After', 'codepress-admin-columns' ) )
		        ->set( 'description', __( 'This text will appear after the column value.', 'codepress-admin-columns' ) )
		        ->set( 'settings', $this->add_element( 'after' ) );

		$view->set( 'settings', $settings );

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