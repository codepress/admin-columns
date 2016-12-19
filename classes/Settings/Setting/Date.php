<?php

class AC_Settings_Setting_Date extends AC_Settings_Setting
	implements AC_Settings_FormatInterface {

	private $date_format;

	protected function set_name() {
		$this->name = 'date';
	}

	protected function define_options() {
		return array(
			'date_format' => get_option( 'date_format' ),
		);
	}

	public function create_view() {
		// todo: dropdown date + custom input
		$date = $this->create_element( 'text' )
		             ->set_attribute( 'placeholder', $this->get_default() )
		             ->set_description( sprintf( __( "Leave empty for WordPress date format, change your <a href='%s'>default date format here</a>.", 'codepress-admin-columns' ), admin_url( 'options-general.php' ) . '#date_format_custom_radio' ) . " <a target='_blank' href='http://codex.wordpress.org/Formatting_Date_and_Time'>" . __( 'Documentation on date and time formatting.', 'codepress-admin-columns' ) . "</a>" );

		$view = new AC_View( array(
			'setting' => $date,
			'label'   => __( 'Date Format', 'codepress-admin-columns' ),
			'tooltip' => __( 'This will determine how the date will be displayed.', 'codepress-admin-columns' ),
		) );

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
	 * @return bool
	 */
	public function set_date_format( $date_format ) {
		$this->date_format = trim( $date_format );

		return true;
	}

	/**
	 * @param string $date
	 *
	 * @return string
	 */
	public function format( $date ) {
		return ac_helper()->date->date( $date, $this->get_date_format() );
	}

}