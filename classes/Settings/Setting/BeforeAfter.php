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

	protected function set_id() {
		$this->id = 'before_after';
	}

	protected function set_managed_options() {
		$this->managed_options = array( 'before', 'after' );
	}

	public function format( $value ) {
		return $this->get_before() . $value . $this->get_after();
	}

	public function view() {
		$before = new AC_Settings_View();
		$before->set( 'label', __( 'Before', 'codepress-admin-columns' ) )
		       ->set( 'description', __( 'This text will appear before the column value.', 'codepress-admin-columns' ) )
		       ->set( 'setting', $this->create_element( 'before' ) );

		$after = new AC_Settings_View();
		$after->set( 'label', __( 'After', 'codepress-admin-columns' ) )
		      ->set( 'description', __( 'This text will appear after the column value.', 'codepress-admin-columns' ) )
		      ->set( 'setting', $this->create_element( 'after' ) );

		$view = new AC_Settings_View();
		$view->set( 'label', __( 'Display Options', 'codepress-admin-columns' ) )
		     ->set( 'sections', array( $before, $after ) );

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