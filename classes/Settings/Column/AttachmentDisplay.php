<?php

class AC_Settings_Column_AttachmentDisplay extends AC_Settings_Column
	implements AC_Settings_FormatValueInterface {

	private $attachment_display;

	protected function define_options() {
		return array(
			'attachment_display' => 'thumbnail',
		);
	}

	public function get_dependent_settings() {
		$settings = array();

		switch ( $this->get_attachment_display() ) {
			case 'thumbnail' :
				$settings[] = new AC_Settings_Column_Images( $this->column );

				break;
		}

		return $settings;
	}

	public function create_view() {

		$setting = $this->create_element( 'select' )
		                ->set_attribute( 'data-refresh', 'column' )
		                ->set_options( array(
			                'thumbnail' => __( 'Thumbnails', 'codepress-admin-columns' ),
			                'count'     => __( 'Count', 'codepress-admin-columns' ),
		                ) );

		$view = new AC_View( array(
			'label'   => __( 'Display', 'codepress-admin-columns' ),
			'setting' => $setting,
		) );

		return $view;
	}

	/**
	 * @return int
	 */
	public function get_attachment_display() {
		return $this->attachment_display;
	}

	/**
	 * @param int $attachment_display
	 *
	 * @return bool
	 */
	public function set_attachment_display( $attachment_display ) {
		$this->attachment_display = $attachment_display;

		return true;
	}

	public function format( $value, $original_value ) {
		switch ( $this->get_attachment_display() ) {
			case 'count':
				$value = count( $value );
				break;
		}

		return $value;
	}
}