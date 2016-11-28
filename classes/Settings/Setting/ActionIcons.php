<?php

class AC_Settings_Setting_ActionIcons extends AC_Settings_SettingAbstract {

	private $use_icons = '';

	protected function set_managed_options() {
		$this->managed_options = array( 'use_icons' );
	}

	protected function create_view() {

		$setting = $this->create_element( 'radio' )
		                ->set_options( array(
			                '1' => __( 'Yes' ),
			                ''  => __( 'No' ),
		                ) );

		$view = new AC_View( array(
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
	 * @return $this
	 */
	public function set_use_icons( $use_icons ) {
		$this->use_icons = $use_icons;

		return $this;
	}

}