<?php

class AC_Settings_Setting_BeforeAfter extends AC_Settings_Setting
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

	protected function define_options() {
		return array( 'before', 'after' );
	}

	public function format( AC_Value $value ) {
		if ( $this->get_before() || $this->get_after() ) {
			$value->add_wrapper( $this->before, $this->after );
		}

		return $value;
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
	 * @param $before
	 *
	 * @return bool
	 */
	public function set_before( $before ) {
		$this->before = $before;

		return true;
	}

	/**
	 * @return string
	 */
	public function get_after() {
		return $this->after;
	}

	/**
	 * @param $after
	 *
	 * @return bool
	 */
	public function set_after( $after ) {
		$this->after = $after;

		return true;
	}

}