<?php

namespace AC\Settings\Column;

use AC\Settings;
use AC\View;

abstract class DateTimeFormat extends Settings\Column
	implements Settings\FormatValue {

	private $date_format;

	protected function set_name() {
		$this->name = 'date';
	}

	protected function define_options() {
		return array(
			'date_format' => 'wp_default',
		);
	}

	abstract protected function get_custom_format_options();

	abstract protected function get_wp_default_format();

	/**
	 * @param string $label
	 * @param string $date_format
	 * @param string $description
	 *
	 * @return string
	 */
	protected function get_default_html_label( $label, $date_format = '', $description = '' ) {
		if ( ! $date_format ) {
			$date_format = $this->get_wp_default_format();
		}

		if ( ! $description && current_user_can( 'manage_options' ) ) {
			$description = sprintf(
				__( 'The %s can be changed in %s.', 'codepress-admin-columns' ),
				$label,
				ac_helper()->html->link( ac_get_admin_url() . '#date_format_custom_radio', strtolower( __( 'General Settings' ) ) )
			);
		}

		return $this->get_html_label( $label, $date_format, $description );
	}

	public function create_view() {
		$setting = $this
			->create_element( 'text' )
			->set_attribute( 'placeholder', $this->get_default() );

		$view = new View( array(
			'setting'      => $setting,
			'date_format'  => $this->get_date_format(),
			'date_options' => $this->get_date_options(),
			'label'        => __( 'Date Format', 'codepress-admin-columns' ),
			'tooltip'      => __( 'This will determine how the date will be displayed.', 'codepress-admin-columns' ),
		) );

		$view->set_template( 'settings/setting-date' );

		return $view;
	}

	public function get_html_label_from_date_format( $date_format ) {
		return $this->get_html_label( date_i18n( $date_format ), $date_format );
	}

	/**
	 * @param array $formats
	 *
	 * @return array
	 */
	protected function get_formatted_date_options( $formats ) {
		$options = array();

		foreach ( $formats as $format ) {
			$options[ $format ] = $this->get_html_label( date_i18n( $format ), $format );
		}

		return $options;
	}

	/**
	 * @param string $label
	 * @param string $date_format
	 * @param string $description
	 *
	 * @return string
	 */
	protected function get_html_label( $label, $date_format = '', $description = '' ) {
		$output = '<span class="ac-setting-input-date__value">' . $label . '</span>';

		if ( $date_format ) {
			$output .= '<code>' . $date_format . '</code>';
		}

		if ( $description ) {
			$output .= '<span class="ac-setting-input-date__more hidden">' . $description . '</span>';
		}

		return $output;
	}

	protected function get_date_options() {
		$options = $this->get_custom_format_options();

		$custom_label = $this->get_html_label(
			__( 'Custom:', 'codepress-admin-columns' ),
			'',
			sprintf( __( 'Learn more about %s.', 'codepress-admin-columns' ), ac_helper()->html->link( 'http://codex.wordpress.org/Formatting_Date_and_Time', __( 'date and time formatting', 'codepress-admin-columns' ) ), array( 'target' => '_blank' ) )
		);

		$custom_label .= '<input type="text" class="ac-setting-input-date__custom" value="' . esc_attr( $this->get_date_format() ) . '" disabled>';
		$custom_label .= '<span class="ac-setting-input-date__example"></span>';

		$options['custom'] = $custom_label;

		return $options;
	}

	/**
	 * @return mixed
	 */
	public function get_date_format() {
		$date_format = $this->date_format;

		if ( ! $date_format ) {
			$date_format = $this->get_default();
		}

		return $date_format;
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
	 * @param $date
	 *
	 * @return false|int
	 */
	protected function get_timestamp( $date ) {
		if ( empty( $date ) ) {
			return false;
		}

		if ( ! is_scalar( $date ) ) {
			return false;
		}

		if ( is_numeric( $date ) ) {
			return $date;
		}

		return strtotime( $date );
	}

	/**
	 * @param string $date
	 * @param        $original_value
	 *
	 * @return string
	 */
	public function format( $date, $original_value ) {
		$timestamp = $this->get_timestamp( $date );

		if ( ! $timestamp ) {
			return false;
		}

		$date_format = $this->get_date_format();

		switch ( $date_format ) {

			case 'wp_default' :
				$date = date_i18n( $this->get_wp_default_format(), $timestamp );

				break;
			default :
				$date = date_i18n( $date_format, $timestamp );
		}

		return $date;
	}

}