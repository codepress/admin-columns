<?php

namespace AC;

use AC\Admin\Help;
use AC\Admin\Page;

/**
 * @since 2.0
 */
class Admin {

	const MENU_SLUG = 'codepress-admin-columns';

	/**
	 * Settings Page hook suffix
	 *
	 * @since 2.0
	 */
	private $hook_suffix;

	/**
	 * @var Admin\Pages
	 */
	private $pages;

	/**
	 * @since 2.0
	 */
	public function __construct() {
		$this->pages = new Admin\Pages();
		$this->pages
			->register_page( new Page\Columns() )
			->register_page( new Page\Settings() )
			->register_page( new Page\Addons() )
			->register_page( new Page\Help() );
	}

	/**
	 * Register Hooks
	 */
	public function register() {
		$this->pages->register();

		add_action( 'admin_menu', array( $this, 'settings_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
	}

	/**
	 * @return Admin\Pages|false
	 */
	public function get_pages() {
		return $this->pages;
	}

	/**
	 * Admin scripts for this tab
	 */
	public function admin_scripts() {
		if ( ! $this->is_admin_screen() ) {
			return;
		}

		wp_enqueue_script( 'ac-admin-general', AC()->get_url() . "assets/js/admin-general.js", array( 'jquery', 'wp-pointer' ), AC()->get_version() );
		wp_enqueue_style( 'wp-pointer' );
		wp_enqueue_style( 'ac-admin', AC()->get_url() . "assets/css/admin-general.css", array(), AC()->get_version() );

		do_action( 'ac/admin_scripts', $this );
	}

	/**
	 * @param $option
	 *
	 * @return bool
	 */
	public function get_general_option( $option ) {
		/* @var Page\Settings $settings */
		$settings = $this->get_pages()->get_page( 'settings' );

		return $settings->get_option( $option );
	}

	/**
	 * @param $slug
	 *
	 * @return Admin\Page\Columns|Admin\Page\Settings|Admin\Page\Addons|false
	 */
	public function get_page( $slug ) {
		return $this->get_pages()->get_page( $slug );
	}

	/**
	 * @param string $tab_slug
	 *
	 * @return false|string URL
	 */
	public function get_link( $slug ) {
		return $this->get_pages()->get_page( $slug )->get_link();
	}

	/**
	 * @since 3.1.1
	 */
	public function get_hook_suffix() {
		return $this->hook_suffix;
	}

	/**
	 * @return string
	 */
	private function get_parent_slug() {
		return 'options-general.php';
	}

	/**
	 * @return string
	 */
	public function get_settings_url() {
		return add_query_arg( array( 'page' => self::MENU_SLUG ), admin_url( $this->get_parent_slug() ) );
	}

	/**
	 * @since 1.0
	 */
	public function settings_menu() {
		$this->hook_suffix = add_submenu_page( $this->get_parent_slug(), __( 'Admin Columns Settings', 'codepress-admin-columns' ), __( 'Admin Columns', 'codepress-admin-columns' ), 'manage_admin_columns', self::MENU_SLUG, array( $this, 'display' ) );

		add_action( 'load-' . $this->hook_suffix, array( $this, 'load_help_tabs' ) );
	}

	/**
	 * Load help tabs
	 */
	public function load_help_tabs() {
		new Help\Introduction();
		new Help\Basics();
		new Help\CustomField();
	}

	/**
	 * @return bool
	 */
	public function is_admin_screen() {
		global $pagenow;

		return self::MENU_SLUG === filter_input( INPUT_GET, 'page' ) && $this->get_parent_slug() === $pagenow;
	}

	/**
	 * @param string $slug
	 *
	 * @return bool
	 */
	public function is_current_page( $slug ) {
		$current_tab = $this->get_pages()->get_current_page();

		return $current_tab && $current_tab->get_slug() === $slug && $this->is_admin_screen();
	}

	/**
	 * @since 1.0
	 */
	public function display() {
		$this->pages->display();
	}

}