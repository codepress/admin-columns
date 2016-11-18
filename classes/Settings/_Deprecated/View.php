<?php

class AC_Settings_View extends AC_Settings_ViewAbstract {

	public function __construct( array $data ) {
		parent::__construct( $data );

		$this->set_template( 'section' );
	}

	/**
	 * Default template path
	 *
	 * @return string
	 */
	protected function get_template_path() {
		return dirname( __FILE__ ) . '/templates';
	}

	// implement resolver?
	// load templates by default? = heavy on paper

	// i like new AC_Settings_View
	// AC_Settings_Section = view
	// AC_Settings_View_Section

	//AC_Settings::view( 'section' );

}