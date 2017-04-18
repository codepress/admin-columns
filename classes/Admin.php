<?php

/**
 * @since 2.0
 */
class AC_Admin {

	CONST MENU_SLUG = 'codepress-admin-columns';

	/**
	 * Settings Page hook suffix
	 *
	 * @since 2.0
	 */
	private $hook_suffix;

	/**
	 * @var AC_Admin_Pages
	 */
	private $pages;

	/**
	 * @since 2.0
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'settings_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
	}

	/**
	 * Load pages
	 */
	private function set_pages() {
		$this->pages = new AC_Admin_Pages();

		$this->pages
			->register_page( new AC_Admin_Page_Columns() )
			->register_page( new AC_Admin_Page_Settings() );

		if ( current_user_can( 'install_plugins' ) ) {
			$this->pages->register_page( new AC_Admin_Page_Addons() );
		}

		$this->pages
			->register_page( new AC_Admin_Page_Help() )
			// Hidden
			->register_page( new AC_Admin_Page_Welcome() )
			->register_page( new AC_Admin_Page_Upgrade() );

		do_action( 'ac/admin_pages', $this->pages );
	}

	/**
	 * @return AC_Admin_Pages|false
	 */
	public function get_pages() {
		if ( null === $this->pages ) {
			$this->set_pages();
		}

		return $this->pages;
	}

	/**
	 * Admin scripts for this tab
	 */
	public function admin_scripts() {
		if ( ! $this->is_admin_screen() ) {
			return;
		}

		do_action( 'ac/admin_scripts', $this );

		if ( $page = $this->get_pages()->get_current_page() ) {
			do_action( 'ac/admin_scripts/' . $page->get_slug(), $this );

			$page->admin_scripts();
		}

		// General scripts
		wp_enqueue_script( 'ac-admin-general', AC()->get_plugin_url() . "assets/js/admin-general" . AC()->minified() . ".js", array( 'jquery', 'wp-pointer' ), AC()->get_version() );

		wp_enqueue_style( 'wp-pointer' );
		wp_enqueue_style( 'ac-admin', AC()->get_plugin_url() . "assets/css/admin-general" . AC()->minified() . ".css", array(), AC()->get_version() );
	}

	/**
	 * @param $option
	 *
	 * @return bool
	 */
	public function get_general_option( $option ) {
		/* @var AC_Admin_Page_Settings $settings */
		$settings = $this->get_pages()->get_page( 'settings' );

		return $settings->get_option( $option );
	}

	/**
	 * @param $tab_slug
	 *
	 * @return AC_Admin_Page_Columns|AC_Admin_Page_Settings|AC_Admin_Page_Addons|false
	 */
	public function get_page( $tab_slug ) {
		return $this->get_pages()->get_page( $tab_slug );
	}

	/**
	 * @param string $tab_slug
	 *
	 * @return false|string URL
	 */
	public function get_link( $tab_slug ) {
		return $this->get_pages()->get_page( $tab_slug )->get_link();
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
		new AC_Admin_Help_Introduction();
		new AC_Admin_Help_Basics();
		new AC_Admin_Help_CustomField();
	}

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
		$this->get_pages()->display();
	}

}