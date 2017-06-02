<?php

class AC_Settings_Column_PostFormat extends AC_Settings_Column
	implements AC_Settings_FormatValueInterface {

	/**
	 * @var string
	 */
	private $post_format;

	protected function define_options() {
		return array( 'post_format' );
	}

	public function create_view() {
		$select = $this->create_element( 'select' )
		               ->set_options( array(
			               ''     => __( 'Icon and text', 'codepress-admin-column' ), // default
			               'icon' => __( 'Icon', 'codepress-admin-column' ),
			               'text' => __( 'Text', 'codepress-admin-column' ),
		               ) );

		$view = new AC_View( array(
			'label'   => __( 'Display format', 'codepress-admin-columns' ),
			'setting' => $select,
		) );

		return $view;
	}

	/**
	 * @return string
	 */
	public function get_post_format() {
		return $this->post_format;
	}

	/**
	 * @param string $post_format
	 *
	 * @return true
	 */
	public function set_post_format( $post_format ) {
		$this->post_format = $post_format;

		return true;
	}

	public function format( $value, $original_value ) {
		$format = $value;
		$icon = $this->get_format_icon( $format );

		switch ( $this->get_post_format() ) {
			case 'icon':
				$value = $icon;
				break;
			case 'text':
				$value = esc_html( get_post_format_string( $format ) );
				break;
			default:
				$value = $icon . esc_html( get_post_format_string( $format ) );
		};

		return $value;
	}

	private function get_format_icon( $format ) {
		if ( ! $format ) {
			return '';
		}

		switch ( $format ) {
			case 'link':
				$icon = 'links';
				break;
			default:
				$icon = $format;

		}

		return sprintf( '<span class="ac-post-format-icon dashicons dashicons-format-%s" title="%s"></span>', $icon, esc_html( get_post_format_string( $format ) ) );
	}

}