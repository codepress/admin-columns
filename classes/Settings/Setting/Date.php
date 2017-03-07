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
		$description = sprintf( __( "Leave empty to use the %s.", 'codepress-admin-columns' ), ac_helper()->html->link( admin_url( 'options-general.php' ) . '#date_format_custom_radio', __( 'WordPress date format', 'codepress-admin-columns' ) ) );
		$description .= " " . ac_helper()->html->link( 'http://codex.wordpress.org/Formatting_Date_and_Time', __( 'Documentation on date and time formatting.', 'codepress-admin-columns' ), array( 'target' => '_blank' ) );

		$date = $this->create_element( 'text' )
		             ->set_attribute( 'placeholder', $this->get_default() );

		$view = new AC_View( array(
			'setting'     => $date,
			'date_format' => $this->get_date_format(),
			'label'       => __( 'Date Format', 'codepress-admin-columns' ),
			'tooltip'     => __( 'This will determine how the date will be displayed.', 'codepress-admin-columns' ),
			'description' => $description,
		) );

		$view->set_template( 'settings/setting-date' );

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