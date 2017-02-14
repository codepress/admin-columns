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
	function __construct() {
		add_action( 'admin_menu', array( $this, 'settings_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

		$this->pages = new AC_Admin_Pages();
		$this->pages
			->register_page( new AC_Admin_Page_Columns() )
			->register_page( new AC_Admin_Page_Settings() )
			->register_page( new AC_Admin_Page_Addons() )
			->register_page( new AC_Admin_Page_Help() )

			// Hidden
			->register_page( new AC_Admin_Page_Welcome() )
			->register_page( new AC_Admin_Page_Upgrade() );
	}

	/**
	 * Admin scripts for this tab
	 */
	public function admin_scripts() {
		if ( ! $this->is_admin_screen() ) {
			return;
		}

		// Hook
		do_action( 'ac/admin_scripts', $this );

		// Page scripts
		if ( $page = $this->pages->get_current_page() ) {

			// Hook
			do_action( 'ac/admin_scripts/' . $page->get_slug(), $this );

			$page->admin_scripts();
		}

		// General scripts
		wp_enqueue_script( 'ac-admin-general', AC()->get_plugin_url() . "assets/js/admin-general" . AC()->minified() . ".js", array( 'jquery', 'wp-pointer' ), AC()->get_version() );

		wp_enqueue_style( 'wp-pointer' );
		wp_enqueue_style( 'cpac-admin', AC()->get_plugin_url() . "assets/css/admin-general" . AC()->minified() . ".css", array(), AC()->get_version(), 'all' );
	}

	/**
	 * @param $option
	 *
	 * @return bool
	 */
	public function get_general_option( $option ) {
		/* @var AC_Admin_Page_Settings $settings */
		$settings = $this->pages->get_page( 'settings' );

		return $settings->get_option( $option );
	}

	/**
	 * @param $tab_slug
	 *
	 * @return AC_Admin_Page_Columns|AC_Admin_Page_Settings|AC_Admin_Page_Addons|false
	 */
	public function get_page( $tab_slug ) {
		return $this->pages->get_page( $tab_slug );
	}

	/**
	 * @param string $tab_slug
	 *
	 * @return false|string URL
	 */
	public function get_link( $tab_slug ) {
		return $this->pages->get_page( $tab_slug )->get_link();
	}

	/**
	 * @return AC_Admin_Pages
	 */
	public function get_pages() {
		return $this->pages;
	}

	/**
	 * @since 3.1.1
	 */
	public function get_hook_suffix() {
		return $this->hook_suffix;
	}

	private function get_parent_slug() {
		return 'options-general.php';
	}

	public function get_settings_url() {
		return add_query_arg( array( 'page' => self::MENU_SLUG ), admin_url( $this->get_parent_slug() ) );
	}

	/**
	 * @since 1.0
	 */
	public function settings_menu() {
		$this->hook_suffix = add_submenu_page( $this->get_parent_slug(), __( 'Admin Columns Settings', 'codepress-admin-columns' ), __( 'Admin Columns', 'codepress-admin-columns' ), 'manage_admin_columns', self::MENU_SLUG, array( $this, 'display' ) );

		add_action( 'load-' . $this->hook_suffix, array( $this, 'help_tabs' ) );
	}

	public function is_admin_screen() {
		global $pagenow;

		return self::MENU_SLUG === filter_input( INPUT_GET, 'page' ) && $this->get_parent_slug() === $pagenow;
	}

	public function is_current_page( $slug ) {
		$current_tab = $this->get_pages()->get_current_page();

		return $current_tab && $current_tab->get_slug() === $slug && $this->is_admin_screen();
	}

	/**
	 * Add help tabs to top menu
	 *
	 * @since 1.3.0
	 */
	public function help_tabs() {
		$screen = get_current_screen();

		if ( ! method_exists( $screen, 'add_help_tab' ) ) {
			return;
		}

		$tabs = array(
			array(
				'title'   => __( "Overview", 'codepress-admin-columns' ),
				'content' => "<h5>Admin Columns</h5>
					<p>" . __( "This plugin is for adding and removing additional columns to the administration screens for post(types), pages, media library, comments, links and users. Change the column's label and reorder them.", 'codepress-admin-columns' ) . "</p>",
			),
			array(
				'title'   => __( "Basics", 'codepress-admin-columns' ),
				'content' => "
					<h5>" . __( "Change order", 'codepress-admin-columns' ) . "</h5>
					<p>" . __( "By dragging the columns you can change the order which they will appear in.", 'codepress-admin-columns' ) . "</p>
					<h5>" . __( "Change label", 'codepress-admin-columns' ) . "</h5>
					<p>" . __( "By clicking on the triangle you will see the column options. Here you can change each label of the columns heading.", 'codepress-admin-columns' ) . "</p>
					<h5>" . __( "Change column width", 'codepress-admin-columns' ) . "</h5>
					<p>" . __( "By clicking on the triangle you will see the column options. By using the draggable slider you can set the width of the columns in percentages.", 'codepress-admin-columns' ) . "</p>
				",
			),
			array(
				'title'   => __( "Custom Field", 'codepress-admin-columns' ),
				'content' => "<h5>" . __( "'Custom Field' column", 'codepress-admin-columns' ) . "</h5>
					<p>" . __( "The custom field colum uses the custom fields from posts and users. There are 10 types which you can set.", 'codepress-admin-columns' ) . "</p>
					<ul>
						<li><strong>" . __( "Default", 'codepress-admin-columns' ) . "</strong><br/>" . __( "Value: Can be either a string or array. Arrays will be flattened and values are seperated by a ',' comma.", 'codepress-admin-columns' ) . "</li>
						<li><strong>" . __( "Checkmark", 'codepress-admin-columns' ) . "</strong><br/>" . __( "Value: should be a 1 (one) or 0 (zero).", 'codepress-admin-columns' ) . "</li>
						<li><strong>" . __( "Color", 'codepress-admin-columns' ) . "</strong><br/>" . __( "Value: hex value color, such as #808080.", 'codepress-admin-columns' ) . "</li>
						<li><strong>" . __( "Counter", 'codepress-admin-columns' ) . "</strong><br/>" . __( "Value: Can be either a string or array. This will display a count of the number of times the meta key is used by the item.", 'codepress-admin-columns' ) . "</li>
						<li><strong>" . __( "Date", 'codepress-admin-columns' ) . "</strong><br/>" . sprintf( __( "Value: Can be unix time stamp or a date format as described in the <a href='%s'>Codex</a>. You can change the outputted date format at the <a href='%s'>general settings</a> page.", 'codepress-admin-columns' ), 'http://codex.wordpress.org/Formatting_Date_and_Time', admin_url( 'options-general.php' ) ) . "</li>
						<li><strong>" . __( "Excerpt", 'codepress-admin-columns' ) . "</strong><br/>" . __( "Value: This will show the first 20 words of the Post content.", 'codepress-admin-columns' ) . "</li>
						<li><strong>" . __( "Image", 'codepress-admin-columns' ) . "</strong><br/>" . __( "Value: should contain an image URL or Attachment IDs ( seperated by a ',' comma ).", 'codepress-admin-columns' ) . "</li>
						<li><strong>" . __( "Media Library", 'codepress-admin-columns' ) . "</strong><br/>" . __( "Value: should contain Attachment IDs ( seperated by a ',' comma ).", 'codepress-admin-columns' ) . "</li>
						<li><strong>" . __( "Multiple Values", 'codepress-admin-columns' ) . "</strong><br/>" . __( "Value: should be an array. This will flatten any ( multi dimensional ) array.", 'codepress-admin-columns' ) . "</li>
						<li><strong>" . __( "Numeric", 'codepress-admin-columns' ) . "</strong><br/>" . __( "Value: Integers only.<br/>If you have the 'sorting addon' this will be used for sorting, so you can sort your posts on numeric (custom field) values.", 'codepress-admin-columns' ) . "</li>
						<li><strong>" . __( "Post Titles", 'codepress-admin-columns' ) . "</strong><br/>" . __( "Value: can be one or more Post ID's (seperated by ',').", 'codepress-admin-columns' ) . "</li>
						<li><strong>" . __( "Usernames", 'codepress-admin-columns' ) . "</strong><br/>" . __( "Value: can be one or more User ID's (seperated by ',').", 'codepress-admin-columns' ) . "</li>
						<li><strong>" . __( "Term Name", 'codepress-admin-columns' ) . "</strong><br/>" . __( "Value: should be an array with term_id and taxonomy.", 'codepress-admin-columns' ) . "</li>
					</ul>
				",
			),
		);

		foreach ( $tabs as $k => $tab ) {
			$screen->add_help_tab( array(
				'id'      => 'cpac-tab-' . $k,
				'title'   => $tab['title'],
				'content' => $tab['content'],
			) );
		}
	}

	/**
	 * @since 1.0
	 */
	public function display() {
		$this->pages->display();
	}

}