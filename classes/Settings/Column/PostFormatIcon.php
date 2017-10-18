<?php

class AC_Settings_Column_PostFormatIcon extends AC_Settings_Column
	implements AC_Settings_FormatValueInterface {

	/**
	 * @var bool
	 */
	private $use_icon;

	protected function define_options() {
		return array( 'use_icon' => '1' );
	}

	public function create_view() {

		$setting = $this->create_element( 'radio' )
		                ->set_options( array(
			                '1' => __( 'Yes' ),
			                ''  => __( 'No' ),
		                ) );

		$view = new AC_View( array(
			'label'   => __( 'Use an icon?', 'codepress-admin-columns' ),
			'tooltip' => __( 'Use an icon instead of text for displaying.', 'codepress-admin-columns' ),
			'setting' => $setting,
		) );

		return $view;
	}

	/**
	 * @return int
	 */
	public function get_use_icon() {
		return $this->use_icon;
	}

	/**
	 * @param int $use_icons
	 *
	 * @return bool
	 */
	public function set_use_icon( $use_icon ) {
		$this->use_icon = $use_icon;

		return true;
	}

	private function use_icon() {
		return '1' === $this->get_use_icon();
	}

	/**
	 * @param string $status
	 * @param int    $post_id
	 *
	 * @return string
	 */
	public function format( $format, $post_id ) {

		if ( $this->use_icon() ) {
			$value = $this->column->get_empty_char();

			if ( $format ) {
				$value = ac_helper()->html->tooltip( '<span class="ac-post-state-format post-state-format post-format-icon post-format-' . esc_attr( $format ) . '"></span>', get_post_format_string( $format ) );
			}
		} else {
			$value = __( 'Standard', 'codepress-admin-columns' );

			if ( $format ) {
				$value = esc_html( get_post_format_string( $format ) );
			}
		}

		return $value;
	}

}