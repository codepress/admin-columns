<?php

class AC_Settings_Column_Date extends AC_Settings_Column
	implements AC_Settings_FormatValueInterface {

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
	 * @param AC_ValueFormatter $value_formatter
	 *
	 * @return mixed
	 */
	public function format( AC_ValueFormatter $value_formatter ) {
		switch ( $this->get_date_format() ) {
			case 'diff':
				$value = $this->format_human_time_diff( ac_helper()->date->date( $value_formatter->value, 'U' ) );
				break;
			default:
				$value = ac_helper()->date->date( $value_formatter->value, $this->get_date_format() );
		}

		$value_formatter->value = $value;

		return $value_formatter;
	}

	/**
	 * @param int $date Unix time stamp
	 */
	public function format_human_time_diff( $time ){
		$suffix = $time < current_time( 'U' ) ? __( ' ago', 'codepress-admin-columns' ) : '';
		$prefix = $time > current_time( 'U' ) ? __( 'in ', 'codepress-admin-columns' ) : '';

		return $prefix . human_time_diff( $time, current_time( 'U' ) ) . $suffix;
	}

}