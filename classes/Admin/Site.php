<?php
namespace AC\Admin;

use AC\Admin;

class Site extends Admin {

	const PLUGIN_PAGE = 'codepress-admin-columns';

	public function __construct( PageFactory $page_factory ) {
		parent::__construct( 'options-general.php', $page_factory );
	}

	public function register() {
		add_action( 'admin_menu', array( $this, 'settings_menu' ) );
	}

	public function menu_items() {
		$items = array(
			'columns',
			'settings',
			'addons',
			'help',
		);

		return $items;
	}

}