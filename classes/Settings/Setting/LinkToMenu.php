<?php

class AC_Settings_Setting_LinkToMenu extends AC_Settings_Setting_ToggleAbstract {

	/**
	 * @var string
	 */
	private $link_to_menu = 'off';

	protected function set_managed_options() {
		$this->managed_options = array( 'link_to_menu' );
	}

	public function create_view() {
		$view = parent::create_view();

		$view->set_data( array(
			'label'   => __( 'Link to menu', 'codepress-admin-columns' ),
			'tooltip' => __( 'This will make the title link to the menu.', 'codepress-admin-columns' ),
		) );

		return $view;
	}

	/**
	 * @return string
	 */
	public function get_link_to_menu() {
		return $this->link_to_menu;
	}

	/**
	 * @param string $link_to_menu
	 *
	 * @return $this
	 */
	public function set_link_to_menu( $link_to_menu ) {
		$this->link_to_menu = $link_to_menu;

		return $this;
	}

}