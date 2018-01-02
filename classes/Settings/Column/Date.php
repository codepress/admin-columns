<?php

class AC_Settings_Column_Date extends AC_Settings_Column_DateTimeFormat {

	protected function set_name() {
		$this->name = 'date';
	}

	protected function define_options() {
		return array(
			'date_format' => 'wp_default',
		);
	}

	protected function get_custom_format_options() {
		$default_option = $this->get_default_date_option( array(
			'label' => __( 'WordPress Date Format', 'codepress-admin-columns' ),
		) );

		$options = array(
			'diff' => $this->get_html_label( array(
					'label'       => __( 'Time Difference', 'codepress-admin-columns' ),
					'description' => __( 'The difference is returned in a human readable format.', 'codepress-admin-columns' ) . ' <br/>' . sprintf( __( 'For example: %s.', 'codepress-admin-columns' ), '"' . $this->format_human_time_diff( strtotime( "-1 hour" ) ) . '" ' . __( 'or' ) . ' "' . $this->format_human_time_diff( strtotime( "-2 days" ) ) . '"' ),
				)
			),
		);

		$formats = array(
			'j F Y',
			'Y-m-d',
			'm/d/Y',
			'd/m/Y',
		);

		return array_merge( $options, $default_option, $this->get_formatted_date_options( $formats ) );
	}

	protected function get_wp_default_format() {
		return get_option( 'date_format' );
	}

	/**
	 * @param string $date
	 *
	 * @return string
	 */
	public function format( $date, $original_value ) {
		if ( ! $date || ! is_scalar( $date ) ) {
			return false;
		}

		$date_format = $this->get_date_format();

		if ( ! $date_format ) {
			$date_format = $this->get_default();
		}

		$timestamp = strtotime( $date );

		if ( 'diff' === $date_format ) {
			return $this->format_human_time_diff( $timestamp );
		}

		return parent::format( $date, $original_value );
	}

	/**
	 * @param int $timestamp Unix time stamp
	 *
	 * @return string
	 */
	public function format_human_time_diff( $timestamp ) {
		if ( ! $timestamp ) {
			return false;
		}

		$current_time = current_time( 'timestamp' );

		$tpl = __( '%s ago' );

		if ( $timestamp > $current_time ) {
			$tpl = __( 'in %s', 'codepress-admin-columns' );
		}

		return sprintf( $tpl, human_time_diff( $timestamp, $current_time ) );
	}

}