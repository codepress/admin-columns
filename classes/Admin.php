<?php
namespace AC;

use AC\Admin\Helpable;
use AC\Admin\PageFactory;

class Admin {

	const PLUGIN_PAGE = 'codepress-admin-columns';
	const PARENT_PAGE = 'options-general.php';

	/** @var string */
	private $hook_suffix;

	public function register() {
		add_action( 'admin_menu', array( $this, 'settings_menu' ) );
	}

	/**
	 * @return void
	 */
	public function settings_menu() {
		$this->hook_suffix = add_submenu_page(
			self::PARENT_PAGE,
			__( 'Admin Columns Settings', 'codepress-admin-columns' ),
			__( 'Admin Columns', 'codepress-admin-columns' ),
			Capabilities::MANAGE,
			self::PLUGIN_PAGE,
			function () {
			}
		);

		add_action( "load-" . $this->hook_suffix, array( $this, 'init' ) );
		add_action( "admin_print_scripts-" . $this->hook_suffix, array( $this, 'admin_scripts' ) );
	}

	/**
	 * @return void
	 */
	public function init() {
		$page = PageFactory::create( filter_input( INPUT_GET, 'tab' ) );

		if ( $page instanceof Registrable ) {
			$page->register();
		}

		// Register help tabs
		if ( $page instanceof Helpable ) {
			foreach ( $page->get_help_tabs() as $help ) {
				get_current_screen()->add_help_tab( array(
					'id'      => $help->get_id(),
					'content' => $help->get_content(),
					'title'   => $help->get_title(),
				) );
			}
		}

		// Page render callback
		add_action( $this->hook_suffix, array( $page, 'render' ) );
	}

	/**
	 * Scripts
	 * @return void
	 */
	public function admin_scripts() {
		wp_enqueue_script( 'ac-admin-general', AC()->get_url() . "assets/js/admin-general.js", array( 'jquery', 'wp-pointer' ), AC()->get_version() );
		wp_enqueue_style( 'wp-pointer' );
		wp_enqueue_style( 'ac-admin', AC()->get_url() . "assets/css/admin-general.css", array(), AC()->get_version() );

		do_action( 'ac/admin_scripts' );
	}

}