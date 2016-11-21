<?php

class AC_Settings_Setting_Label extends AC_Settings_SettingAbstract {

	/**
	 * @var string
	 */
	private $label;

	protected function set_properties() {
		$this->properties = array( 'label' );
	}

	public function view() {
		$view = new AC_Settings_View();

		// return an empty view
		if ( $this->column->is_original() && ac_helper()->string->contains_html_only( $this->label ) ) {
			$view->set_template( false );

			return $view;
		}

		$setting = $this->create_element( 'label' )
		                ->set_attribute( 'placeholder', $this->column->get_type() );

		$view->set( 'settings', $setting )
		     ->set( 'label', __( 'Label', 'codepress-admin-columns' ) )
		     ->set( 'description', __( 'This is the name which will appear as the column header.', 'codepress-admin-columns' ) );

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
		$this->label = $label;

		return $this;
	}

}