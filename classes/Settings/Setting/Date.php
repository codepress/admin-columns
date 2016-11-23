<?php

class AC_Settings_Setting_Date extends AC_Settings_SettingAbstract
	implements AC_Settings_FormatInterface {

	private $date_format;

	protected function set_name() {
		$this->name = 'date';
	}

	protected function set_managed_options() {
		$this->managed_options = array( 'date_format' );
	}

	public function view() {
		// TODO: dropdown date + custom input
		$date = $this->create_element()
		             ->set_attribute( 'placeholder', __( 'Example:', 'codepress-admin-columns' ) . ' d M Y H:i' )
		             ->set_description( sprintf( __( "Leave empty for WordPress date format, change your <a href='%s'>default date format here</a>.", 'codepress-admin-columns' ), admin_url( 'options-general.php' ) . '#date_format_custom_radio' ) . " <a target='_blank' href='http://codex.wordpress.org/Formatting_Date_and_Time'>" . __( 'Documentation on date and time formatting.', 'codepress-admin-columns' ) . "</a>" );

		$view = $this->get_view();
		$view->set( 'setting', $date )
		     ->set( 'label', __( 'Date Format', 'codepress-admin-columns' ) )
		     ->set( 'tooltip', __( 'This will determine how the date will be displayed.', 'codepress-admin-columns' ) );

		return $view;
	}

	/**
	 * @return mixed
	 */
	public function get_date_format() {
		return $this->date_format;
	}

	/**
	 * @param mixed $date_format
	 *
	 * @return $this
	 */
	public function set_date_format( $date_format ) {
		$this->date_format = $date_format;

		return $this;
	}

	public function format( $value ) {
		return $value;
	}

}