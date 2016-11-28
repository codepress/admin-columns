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

	protected function set_name() {
		$this->name = 'before_after';
	}

	protected function set_managed_options() {
		$this->managed_options = array( 'before', 'after' );
	}

	public function format( $value ) {
		return $this->get_before() . $value . $this->get_after();
	}

	public function create_view() {
		$before = new AC_View( array(
			'label'       => __( 'Before', 'codepress-admin-columns' ),
			'description' => __( 'This text will appear before the column value.', 'codepress-admin-columns' ),
			'setting'     => $this->create_element( 'text', 'before' ),
		) );

		$after = new AC_View( array(
			'label'       => __( 'After', 'codepress-admin-columns' ),
			'description' => __( 'This text will appear after the column value.', 'codepress-admin-columns' ),
			'setting'     => $this->create_element( 'text', 'after' ),
		) );

		$view = new AC_View( array(
			'label'    => __( 'Display Options', 'codepress - admin - columns' ),
			'sections' => array( $before, $after ),
		) );

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