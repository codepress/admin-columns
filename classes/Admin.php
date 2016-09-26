<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Admin {

	CONST PAGE_SLUG = 'codepress-admin-columns';

	/**
	 * Settings Page
	 *
	 * @since 2.0
	 */
	private $settings_page;

	private $tabs;

	/**
	 * @since 2.0
	 *
	 * @param object CPAC
	 */
	function __construct() {

		add_action( 'admin_menu', array( $this, 'settings_menu' ) );
		add_action( 'cpac_messages', array( $this, 'maybe_display_addon_statuschange_message' ) );

		$tabs = new AC_Settings_Tabs();
		$tabs->register_tab( new AC_Settings_Tab_Columns() );
		$tabs->register_tab( new AC_Settings_Tab_Settings() );
		$tabs->register_tab( new AC_Settings_Tab_Addons() );

		$this->tabs = $tabs;
	}

	/**
	 * @param $option
	 *
	 * @return bool
	 */
	public function get_general_option( $option ) {
		return $this->get_settings_tab()->get_option( $option );
	}

	/**
	 * @return AC_Settings_Tab_Settings
	 */
	public function get_settings_tab() {
		return $this->tabs->get_tab( 'settings' );
	}

	/**
	 * @param string $tab_slug
	 *
	 * @return false|string URL
	 */
	public function get_link( $tab_slug ) {
		return $this->tabs->get_tab( $tab_slug )->get_link();
	}

	/**
	 * @return AC_Settings_Tabs
	 */
	public function get_tabs() {
		return $this->tabs;
	}

	/**
	 * @since 3.1.1
	 */
	public function get_settings_page() {
		return $this->settings_page;
	}

	public function get_settings_url() {
		return admin_url( add_query_arg( array( 'page' => self::PAGE_SLUG ), 'options-general.php' ) );
	}

	public function get_upgrade_url() {
		return admin_url( add_query_arg( array( 'page' => 'cpac-upgrade' ), 'options-general.php' ) );
	}

	public function get_welcome_url() {
		return add_query_arg( array( 'info' => 1 ), $this->get_settings_url() );
	}

	/**
	 * Display an activation/deactivation message on the addons page if applicable
	 *
	 * @since 2.2
	 */
	public function maybe_display_addon_statuschange_message() {
		if ( empty( $_REQUEST['tab'] ) || $_REQUEST['tab'] != 'addons' ) {
			return;
		}

		$message = '';

		if ( ! empty( $_REQUEST['activate'] ) ) {
			$message = __( 'Add-on successfully activated.', 'codepress-admin-columns' );
		}
		else if ( ! empty( $_REQUEST['deactivate'] ) ) {
			$message = __( 'Add-on successfully deactivated.', 'codepress-admin-columns' );
		}

		if ( ! $message ) {
			return;
		}
		?>
		<div class="updated cac-notification below-h2">
			<p><?php echo $message; ?></p>
		</div>
		<?php
	}

	/**
	 * @since 1.0
	 */
	public function settings_menu() {
		$this->settings_page = add_submenu_page( 'options-general.php', __( 'Admin Columns Settings', 'codepress-admin-columns' ), __( 'Admin Columns', 'codepress-admin-columns' ), 'manage_admin_columns', self::PAGE_SLUG, array( $this, 'display' ) );

		add_filter( 'option_page_capability_cpac-general-settings', array( $this, 'add_capability' ) );
		add_action( 'load-' . $this->settings_page, array( $this, 'help_tabs' ) );

		add_action( 'admin_print_styles-' . $this->settings_page, array( $this, 'admin_styles' ) );
		add_action( 'admin_print_scripts-' . $this->settings_page, array( $this, 'admin_scripts' ) );
	}

	/**
	 * Allows the capability 'manage_admin_columns' to store data through /wp-admin/options.php
	 *
	 * @since 2.0
	 */
	public function add_capability() {
		return 'manage_admin_columns';
	}

	/**
	 * @since 1.0
	 */
	public function admin_styles() {
		$minified = AC()->minified();

		wp_enqueue_style( 'wp-pointer' );
		wp_enqueue_style( 'cpac-admin', AC()->get_plugin_url() . "assets/css/admin-column{$minified}.css", array(), AC()->get_version(), 'all' );
	}

	public function is_admin_screen() {
		global $pagenow;

		return self::PAGE_SLUG === filter_input( INPUT_GET, 'page' ) && 'options-general.php' === $pagenow;
	}

	public function is_current_tab( $tab_slug ) {
		return $this->get_tabs()->get_current_slug() === $tab_slug && $this->is_admin_screen();
	}

	/**
	 * @since 1.0
	 */
	public function admin_scripts() {

		do_action( 'ac/enqueue_settings_scripts' );

		// width slider
		wp_enqueue_style( 'jquery-ui-lightness', AC()->get_plugin_url() . 'assets/ui-theme/jquery-ui-1.8.18.custom.css', array(), AC()->get_version(), 'all' );
		wp_enqueue_script( 'jquery-ui-slider' );

		$minified = AC()->minified();

		wp_enqueue_script( 'cpac-admin-settings', AC()->get_plugin_url() . "assets/js/admin-settings{$minified}.js", array(
			'jquery',
			'dashboard',
			'jquery-ui-slider',
			'jquery-ui-sortable',
			'wp-pointer',
		), AC()->get_version() );

		// javascript translations
		wp_localize_script( 'cpac-admin-settings', 'cpac_i18n', array(
			'clone' => __( '%s column is already present and can not be duplicated.', 'codepress-admin-columns' ),
			'error' => __( 'Invalid response.', 'codepress-admin-columns' ),
		) );

		// nonce
		wp_localize_script( 'cpac-admin-settings', 'cpac', array(
			'_ajax_nonce' => wp_create_nonce( 'cpac-settings' ),
		) );

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
						<li><strong>" . __( "Date", 'codepress-admin-columns' ) . "</strong><br/>" . sprintf( __( "Value: Can be unix time stamp or a date format as described in the <a href='%s'>Codex</a>. You can change the outputted date format at the <a href='%s'>general settings</a> page.", 'codepress-admin-columns' ), 'http://codex.wordpress.org/Formatting_Date_and_Time', get_admin_url() . 'options-general.php' ) . "</li>
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
		$welcome_screen = new AC_Settings_Welcome();

		if ( $welcome_screen->has_upgrade_run() ) {
			$welcome_screen->display();

			return;
		}

		$this->tabs->display();
	}

}