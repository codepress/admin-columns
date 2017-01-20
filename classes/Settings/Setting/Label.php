<?php

class AC_Settings_Setting_Label extends AC_Settings_Setting {

	/**
	 * @var string
	 */
	private $label;

	protected function define_options() {
		return array(
			'label' => $this->column->get_label(),
		);
	}

	public function create_view() {
		$view = new AC_View( array(
			'label'   => __( 'Label', 'codepress-admin-columns' ),
			'tooltip' => __( 'This is the name which will appear as the column header.', 'codepress-admin-columns' ),
			'setting' => $this->create_element( 'text' )->set_attribute( 'placeholder', $this->column->get_label() ),
		) );

		return $view;
	}

	/**
	 * Convert site_url() to [cpac_site_url] and back for easy migration
	 *
	 * @param string $label
	 * @param string $action
	 *
	 * @return string
	 */
	private function convert_site_url( $label, $action = 'encode' ) {
		$input = array( site_url(), '[cpac_site_url]' );

		if ( 'decode' == $action ) {
			$input = array_reverse( $input );
		}

		return stripslashes( str_replace( $input[0], $input[1], trim( $label ) ) );
	}

	/**
	 * @return string
	 */
	public function get_label() {
		return $this->convert_site_url( $this->label, 'decode' );
	}

	/**
	 * @param string $label
	 */
	public function set_label( $label ) {
		$this->label = $this->convert_site_url( $label );
	}

}