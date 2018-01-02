<?php

class AC_Settings_Column_Time extends AC_Settings_Column_DateTimeFormat
	implements AC_Settings_FormatValueInterface {

	public function create_view() {
		$view = parent::create_view();

		$view->set( 'label', 'BLABLA' );

		return $view;
	}

	protected function get_custom_format_options() {
		$options['wp_default'] = $this->get_default_html_label( __( 'WordPress Time Format', 'codepress-admin-columns' ) );

		$formats = array(
			'H:i:s',
			'g:i A',
		);

		foreach ( $formats as $format ) {
			$options[ $format ] = $this->get_html_label_from_date_format( $format );
		}

		return $options;
	}

	protected function get_wp_default_format() {
		return get_option( 'time_format' );
	}

}