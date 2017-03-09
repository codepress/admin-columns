<?php

class AC_Settings_Column_LinkToMenu extends AC_Settings_Column_Toggle {

	/**
	 * @var string
	 */
	private $link_to_menu;

	protected function define_options() {
		return array(
			'link_to_menu' => 'on',
		);
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
	 * @return bool
	 */
	public function set_link_to_menu( $link_to_menu ) {
		$this->link_to_menu = $link_to_menu;

		return true;
	}

}