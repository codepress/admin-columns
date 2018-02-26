<?php

class AC_Settings_Column_Label extends AC_Settings_Column {

	/**
	 * @var string
	 */
	private $label;

	/**
	 * @var string
	 */
	private $label_type;

	protected function define_options() {
		return array(
			'label'      => $this->column->get_label(),
			'label_type' => 'text',
		);
	}

	public function create_view() {

		$setting = $this
			->create_element( 'text' )
			->set_attribute( 'placeholder', $this->column->get_label() );

		$type = new AC_View( array(
			'setting' => $this->create_element( 'text', 'label_type' ),
			'label'   => __( 'Type', 'codepress-admin-columns' ),
		) );

		$view = new AC_View( array(
			'label'    => __( 'Label', 'codepress-admin-columns' ),
			'tooltip'  => __( 'This is the name which will appear as the column header.', 'codepress-admin-columns' ),
			'setting'  => $setting,
			'sections' => array( $type ),
		) );

		$view->set_template( 'settings/setting-label' );

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
		$this->label = $label;
	}

	/**
	 * Encode label with site_url.
	 * Used when loading the setting from PHP or when a site is migrated to another domain.
	 *
	 * @return string
	 */
	public function get_encoded_label() {
		return $this->convert_site_url( $this->label );
	}

	/**
	 * @return string
	 */
	public function get_label_type() {
		return $this->label_type;
	}

	/**
	 * @param string $label_type
	 */
	public function set_label_type( $label_type ) {
		$this->label_type = $label_type;
	}
}