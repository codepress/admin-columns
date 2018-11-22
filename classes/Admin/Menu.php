<?php
namespace AC\Admin;

use AC\Capabilities;

class Menu {

	const MENU_SLUG = 'codepress-admin-columns';

	/** @var string */
	private $hook_suffix;

	public function register() {
		add_action( 'admin_menu', array( $this, 'settings_menu' ) );
	}

	public function settings_menu() {
		$this->hook_suffix = add_submenu_page(
			'options-general.php',
			__( 'Admin Columns Settings', 'codepress-admin-columns' ),
			__( 'Admin Columns 2', 'codepress-admin-columns' ),
			Capabilities::MANAGE,
			self::MENU_SLUG,
			array( $this, 'render' )
		);
	}

	/**
	 * Action usage for hook suffix is: add_action( 'load-' . $hooksuffix );
	 *
	 * @return string
	 */
	public function get_hook_suffix() {
		return $this->hook_suffix;
	}

	public function render() {

	}

}