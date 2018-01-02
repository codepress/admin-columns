<?php

class AC_Settings_Column_Time extends AC_Settings_Column_DateTimeFormat
	implements AC_Settings_FormatValueInterface {

	protected function get_custom_format_options() {
		$options = $this->get_default_date_option( array(
			'label' => __( 'WordPress Time Format', 'codepress-admin-columns' ),
		) );

		$formats = array(
			'H:i:s',
			'g:i A',
		);

		return array_merge( $options, $this->get_formatted_date_options( $formats ) );
	}

	protected function get_wp_default_format() {
		return get_option( 'time_format' );
	}

}