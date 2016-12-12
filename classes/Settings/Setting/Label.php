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
		if ( $this->column->is_original() && ac_helper()->string->contains_html_only( $this->get_label() ) ) {
			return false;
		}

		$label = $this->create_element( 'text' )
		              ->set_attribute( 'placeholder', $this->column->get_label() );

		$view = new AC_View( array(
			'label'   => __( 'Label', 'codepress-admin-columns' ),
			'tooltip' => __( 'This is the name which will appear as the column header.', 'codepress-admin-columns' ),
			'setting' => $label,
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
		$label = $this->convert_site_url( $this->label, 'decode' );

		return $label;
	}

	/**
	 * @param string $label
	 *
	 * @return bool
	 */
	public function set_label( $label ) {
		$label = $this->convert_site_url( $label );

		// Label can not contains the character ":" and "'", exception are data url's
		// TODO: move to sorting
		if ( false === strpos( $label, 'data:' ) ) {
			$label = str_replace( array( ':', "'" ), '', $label );
		}

		$this->label = $label;

		return true;
	}

}