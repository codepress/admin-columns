<?php

class AC_Settings_Column_MissingImageSize extends AC_Settings_Column {

	private $include_missing_sizes;

	protected function define_options() {
		return array(
			'include_missing_sizes' => '',
		);
	}

	public function create_view() {

		$setting = $this->create_element( 'radio' )
		                ->set_options( array(
			                '1' => __( 'Yes' ),
			                ''  => __( 'No' ),
		                ) );

		$view = new AC_View( array(
			'label'   => __( 'Include missing sizes?', 'codepress-admin-columns' ),
			'tooltip' => __( 'Include sizes that are missing an image file.', 'codepress-admin-columns' ),
			'setting' => $setting,
		) );

		return $view;
	}

	/**
	 * @return int
	 */
	public function get_include_missing_sizes() {
		return $this->include_missing_sizes;
	}

	/**
	 * @param int $include_missing_sizes
	 *
	 * @return bool
	 */
	public function set_include_missing_sizes( $include_missing_sizes ) {
		$this->include_missing_sizes = $include_missing_sizes;

		return true;
	}

}