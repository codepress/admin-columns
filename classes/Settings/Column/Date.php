<?php

class AC_Settings_Column_Date extends AC_Settings_Column
	implements AC_Settings_FormatValueInterface {

	private $date_format;

	protected function set_name() {
		$this->name = 'date';
	}

	protected function define_options() {
		return array(
			'date_format' => 'wp_default',
		);
	}

	public function create_view() {

		$setting = $this
			->create_element( 'text' )
			->set_attribute( 'placeholder', $this->get_default() );

		$view = new AC_View( array(
			'setting'      => $setting,
			'date_format'  => $this->get_date_format(),
			'date_options' => $this->get_date_options(),
			'label'        => __( 'Date Format', 'codepress-admin-columns' ),
			'tooltip'      => __( 'This will determine how the date will be displayed.', 'codepress-admin-columns' ),
		) );

		$view->set_template( 'settings/setting-date' );

		return $view;
	}

	private function get_html_label( $label ) {
		return '<span class="ac-setting-input-date__value">' . $label . '</span>';
	}

	private function get_date_options() {

		$options = array(
			'diff'       => __( 'Readable time difference', 'codepress-admin-columns' ) . ' (' . sprintf( __( '%s ago' ), human_time_diff( strtotime( '-1 hour' ) ) ) . ')',
			'wp_default' => $this->get_html_label( __( 'General date format', 'codepress-admin-columns' ) ) . '<code>' . $this->get_wp_date_format() . '</code>',
		);

		if ( current_user_can( 'manage_options' ) ) {
			$class = 'wp_default' !== $this->get_date_format() ? ' hidden' : '';
			$options['wp_default'] .= '<span class="ac-setting-input-date__more' . $class . '">' . ac_helper()->html->link( admin_url( 'options-general.php' ) . '#date_format_custom_radio', strtolower( __( 'General Settings' ) ) ) . '<span>';
		}

		$formats = array(
			'j F Y',
			'Y-m-d',
			'm/d/Y',
			'd/m/Y',
		);

		foreach ( $formats as $format ) {
			$options[ $format ] = $this->get_html_label( date_i18n( $format ) ) . '<code>' . $format . '</code>';
		}

		$custom_format = $this->get_date_format();

		if ( 'wp_default' === $this->get_date_format() ) {
			$custom_format = $this->get_wp_date_format();
		}

		if ( 'diff' === $this->get_date_format() ) {
			$custom_format = false;
		}

		$options['custom'] = $this->get_html_label( __( 'Custom:', 'codepress-admin columns' ) );
		$options['custom'] .= '<input type="text" class="ac-setting-input-date__custom" value="' . esc_attr( $custom_format ) . '" disabled>';
		$options['custom'] .= '<span class="ac-setting-input-date__example">' . ( $custom_format ? esc_html( date_i18n( $custom_format ) ) : '' ) . '</span>';
		$options['custom'] .= '<p class="help-msg hidden">' . ac_helper()->html->link( 'http://codex.wordpress.org/Formatting_Date_and_Time', __( 'Documentation on date and time formatting.', 'codepress-admin-columns' ), array( 'target' => '_blank' ) ) . '</p>';

		return $options;
	}

	private function get_wp_date_format() {
		return get_option( 'date_format' );
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
		if ( $date_format = $this->get_date_format() ) {

			if ( 'diff' === $date_format ) {
				$value = $this->format_human_time_diff( ac_helper()->date->date( $value_formatter->value, 'U' ) );
			} else {
				$value = ac_helper()->date->date( $value_formatter->value, $date_format );
			}
		} else {
			$value = ac_helper()->date->date( $value_formatter->value, get_option( 'date_format' ) );
		}

		$value_formatter->value = $value;

		return $value_formatter;
	}

	/**
	 * @param int $timestamp Unix time stamp
	 */
	public function format_human_time_diff( $timestamp ) {
		if ( ! $timestamp ) {
			return false;
		}

		$tpl = __( '%s ago' );

		if ( $timestamp > current_time( 'U' ) ) {
			$tpl = __( 'in %s', 'codepress-admin-columns' );
		}

		return sprintf( $tpl, human_time_diff( $timestamp ) );
	}

}