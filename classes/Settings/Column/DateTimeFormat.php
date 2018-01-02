<?php

abstract class AC_Settings_Column_DateTimeFormat extends AC_Settings_Column
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

	abstract protected function get_custom_format_options();

	abstract protected function get_wp_default_format();

	public function get_default_date_option( $args = array() ) {
		$default_args = wp_parse_args( $args, array(
			'label'       => __( 'WordPress Date Format', 'codepress-admin-columns' ),
			'date_format' => $this->get_wp_default_format(),
		) );

		if ( current_user_can( 'manage_options' ) ) {
			$default_args['description'] = sprintf( __( 'The %s can be changed in %s.', 'codepress-admin-columns' ), $default_args['label'], ac_helper()->html->link( admin_url( 'options-general.php' ) . '#date_format_custom_radio', strtolower( __( 'General Settings' ) ) ) );
		}

		return array(
			'wp_default' => $this->get_html_label( $default_args ),
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

	/**
	 * @param array $formats
	 *
	 * @return array
	 */
	protected function get_formatted_date_options( $formats ){
		$options = array();

		foreach ( $formats as $format ) {
			$options[ $format ] = $this->get_html_label( array( 'label' => date_i18n( $format ), 'date_format' => $format ) );
		}

		return $options;
	}

	protected function get_html_label( $args ) {
		$defaults = array(
			'label'       => false,
			'date_format' => false,
			'description' => false,
		);

		$data = (object) wp_parse_args( $args, $defaults );

		$label = '';

		if ( $data->label ) {
			$label .= '<span class="ac-setting-input-date__value">' . $data->label . '</span>';
		}
		if ( $data->date_format ) {
			$label .= '<code>' . $data->date_format . '</code>';
		}
		if ( $data->description ) {
			$label .= '<span class="ac-setting-input-date__more hidden">' . $data->description . '</span>';
		}

		return $label;
	}

	protected function get_date_options() {
		$options = $this->get_custom_format_options();

		$custom_label = $this->get_html_label( array(
				'label'       => __( 'Custom:', 'codepress-admin-columns' ),
				'description' => sprintf( __( 'Learn more about %s.', 'codepress-admin-columns' ), ac_helper()->html->link( 'http://codex.wordpress.org/Formatting_Date_and_Time', __( 'date and time formatting', 'codepress-admin-columns' ) ), array( 'target' => '_blank' ) ),
			)
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
	public function format( $date, $original_value ) {
		if ( ! $date || ! is_scalar( $date ) ) {
			return false;
		}

		$date_format = $this->get_date_format();

		if ( ! $date_format ) {
			$date_format = $this->get_default();
		}

		$timestamp = strtotime( $date );

		switch ( $date_format ) {

			case 'wp_default' :
				$date = date_i18n( $this->get_wp_default_format(), $timestamp );

				break;
			default :
				$date = date_i18n( $this->get_date_format(), $timestamp );
		}

		return $date;
	}

}