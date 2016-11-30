<?php

class AC_Settings_Setting_Label extends AC_Settings_SettingAbstract {

	/**
	 * @var string
	 */
	private $label;

	public function __construct( AC_Column $column ) {
		parent::__construct( $column );

		$this->set_default( $column->get_label() );
	}

	protected function set_managed_options() {
		$this->managed_options = array( 'label' );
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
	 * @return string
	 */
	public function get_label() {
		return $this->label;
	}

	/**
	 * @param string $label
	 *
	 * @return $this
	 */
	public function set_label( $label ) {
		$this->label = trim( $label );

		return $this;
	}

	// TODO: somehow use when storing or maybe always use
	private function sanitize( $label ) {
		if ( $label ) {
			// Local site url will be replaced before storing into DB.
			// This makes it easier when migrating DB to a new install.
			$label = stripslashes( str_replace( site_url(), '[cpac_site_url]', trim( $label ) ) );

			// Label can not contains the character ":"" and "'", because
			// AC_Column::get_sanitized_label() will return an empty string
			// and make an exception for site_url()
			// Enable data:image url's
			if ( false === strpos( $label, 'data:' ) ) {
				$label = str_replace( ':', '', $label );
				$label = str_replace( "'", '', $label );
			}
		}

		return $label;
	}

}