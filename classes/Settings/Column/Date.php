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

		$options = array(
			'diff' => $this->get_html_label( array(
					'label'       => __( 'Time Difference', 'codepress-admin-columns' ),
					'description' => __( 'The difference is returned in a human readable format.', 'codepress-admin-columns' ) . ' <br/>' . sprintf( __( 'For example: %s.', 'codepress-admin-columns' ), '"' . $this->format_human_time_diff( strtotime( "-1 hour" ) ) . '" ' . __( 'or' ) . ' "' . $this->format_human_time_diff( strtotime( "-2 days" ) ) . '"' ),
				)
			),
		);

		$default_args = array(
			'label'       => __( 'WordPress Date Format', 'codepress-admin-columns' ),
			'date_format' => $this->get_wp_date_format(),
		);

		if ( current_user_can( 'manage_options' ) ) {
			$default_args['description'] = sprintf( __( 'The %s can be changed in %s.', 'codepress-admin-columns' ), $default_args['label'], ac_helper()->html->link( admin_url( 'options-general.php' ) . '#date_format_custom_radio', strtolower( __( 'General Settings' ) ) ) );
		}

		$options['wp_default'] = $this->get_html_label( $default_args );

		$formats = array(
			'j F Y',
			'Y-m-d',
			'm/d/Y',
			'd/m/Y',
		);

		foreach ( $formats as $format ) {
			$options[ $format ] = $this->get_html_label( array( 'label' => date_i18n( $format ), 'date_format' => $format ) );
		}

		$custom_label = $this->get_html_label( array(
				'label'       => __( 'Custom:', 'codepress-admin columns' ),
				'description' => sprintf( __( 'Learn more about %s.', 'codepress-admin-columns' ), ac_helper()->html->link( 'http://codex.wordpress.org/Formatting_Date_and_Time', __( 'date and time formatting', 'codepress-admin-columns' ) ), array( 'target' => '_blank' ) ),
			)
		);
		$custom_label .= '<input type="text" class="ac-setting-input-date__custom" value="' . esc_attr( $this->get_date_format() ) . '" disabled>';
		$custom_label .= '<span class="ac-setting-input-date__example"></span>';

		$options['custom'] = $custom_label;

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
	 * @param string $date
	 *
	 * @return string
	 */
	public function format( $date, $original_value ) {
		$timestamp = strtotime( $date );

		switch ( $this->get_date_format() ) {

			case 'wp_default' :
				$date = date_i18n( $this->get_wp_date_format(), $timestamp );

				break;
			case 'diff' :
				$date = $this->format_human_time_diff( $timestamp );

				break;
			default :

				$date = date_i18n( $this->get_date_format(), $timestamp );
		}

		return $date;
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

		$tpl = __( '%s ago' );

		if ( $timestamp > current_time( 'U' ) ) {
			$tpl = __( 'in %s', 'codepress-admin-columns' );
		}

		return sprintf( $tpl, human_time_diff( $timestamp ) );
	}

}