<?php

namespace AC\Settings\Column;

use AC\Settings\Column;
use AC\View;

class ActionIcons extends Column {

	private $use_icons;

	protected function define_options() {
		return array(
			'use_icons' => '',
		);
	}

	public function create_view() {

		$setting = $this->create_element( 'radio' )
		                ->set_options( array(
			                '1' => __( 'Yes' ),
			                ''  => __( 'No' ),
		                ) );

		$view = new View( array(
			'label'   => __( 'Use icons?', 'codepress-admin-columns' ),
			'tooltip' => __( 'Use icons instead of text for displaying the actions.', 'codepress-admin-columns' ),
			'setting' => $setting,
		) );

		return $view;
	}

	/**
	 * @return int
	 */
	public function get_use_icons() {
		return $this->use_icons;
	}

	/**
	 * @param int $use_icons
	 *
	 * @return bool
	 */
	public function set_use_icons( $use_icons ) {
		$this->use_icons = $use_icons;

		return true;
	}

}