<?php

class AC_Settings_Setting_Date extends AC_Settings_SettingAbstract
	implements AC_Settings_FormatInterface {

	private $date;

	protected function set_managed_options() {
		$this->managed_options = array( 'date' );
	}

	public function view() {
		$view = new AC_Settings_View();

		$date = $this->create_element( 'date' )
		             ->set_attribute( 'placeholder', 'Y-m-Y' );

		$view->set( 'setting', $date )
		     ->set( 'label', __( 'Date Format', 'codepress-admin-columns' ) )
		     ->set( 'description', __( 'This is the name which will appear as the column header.', 'codepress-admin-columns' ) );

		return $view;
	}

	/**
	 * @return string
	 */
	public function get_date() {
		return $this->date;
	}

	/**
	 * @param string $date
	 *
	 * @return $this
	 */
	public function set_date( $date ) {
		$this->date = $date;

		return $this;
	}

	public function format( $value ) {
		return $value;
	}

}