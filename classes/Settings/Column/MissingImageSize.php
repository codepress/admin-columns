<?php

namespace AC\Settings\Column;

use AC\Settings;
use AC\View;

class MissingImageSize extends Settings\Column {

	private $include_missing_sizes;

	protected function define_options() {
		return [
			'include_missing_sizes' => '',
		];
	}

	public function create_view() {

		$setting = $this->create_element( 'radio' )
		                ->set_options( [
			                '1' => __( 'Yes' ),
			                ''  => __( 'No' ),
		                ] );

		$view = new View( [
			'label'   => __( 'Include missing sizes?', 'codepress-admin-columns' ),
			'tooltip' => __( 'Include sizes that are missing an image file.', 'codepress-admin-columns' ),
			'setting' => $setting,
		] );

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