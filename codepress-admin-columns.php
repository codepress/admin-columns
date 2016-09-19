<?php
/*
Plugin Name: Admin Columns
Version: 2.6beta
Description: Customize columns on the administration screens for post(types), pages, media, comments, links and users with an easy to use drag-and-drop interface.
Author: AdminColumns.com
Author URI: https://www.admincolumns.com
Plugin URI: https://www.admincolumns.com
Text Domain: codepress-admin-columns
Domain Path: /languages
License: GPLv2

Copyright 2011-2016  AdminColumns.com  info@admincolumns.com

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License version 2 as published by
the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Only run plugin in the admin interface
if ( ! is_admin() ) {
	return false;
}

/**
 * The Admin Columns Class
 *
 * @since 1.0
 *
 * @property AC_Helper helper
 */
class CPAC {

	/**
	 * Admin Columns add-ons class instance
	 *
	 * @since 2.2
	 * @access private
	 * @var AC_Addons
	 */
	private $_addons;

	/**
	 * Admin Columns settings class instance
	 *
	 * @since 2.2
	 * @access private
	 * @var AC_Admin
	 */
	private $_settings;

	/**
	 * Admin Columns plugin upgrade class instance
	 *
	 * @since 2.2.7
	 * @access private
	 * @var AC_Upgrade
	 */
	private $_upgrade;

	/**
	 * Registered storage model class instances
	 * Array of AC_StorageModel instances, with the storage model keys (e.g. post, page, wp-users) as keys
	 *
	 * @since 2.0
	 * @var AC_StorageModel[]
	 */
	private $storage_models;

	/**
	 * @since 2.4.9
	 */
	private $current_storage_model = null;

	/**
	 * @since NEWVERSION
	 */
	private $general_options = null;

	/**
	 * @since NEWVERSION
	 * @var null|string $version Version number
	 */
	private $version = null;

	/**
	 * @since 2.5
	 */
	protected static $_instance = null;

	/**
	 * @since NEWVERSION
	 * @var AC_Helper
	 */
	private $helper;

	/**
	 * @since 2.5
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * @since 1.0
	 */
	function __construct() {

		// Backwards compatibility
		define( 'CPAC_VERSION', $this->get_version() );
		define( 'CPAC_URL', $this->get_plugin_url() );
		define( 'CPAC_DIR', $this->get_plugin_dir() );

		$classes_dir = $this->get_plugin_dir() . 'classes/';

		require_once $classes_dir . 'autoloader.php';

		$autoloader = AC_Autoloader::instance();
		$autoloader->register_prefix( 'AC_', $classes_dir );

		require_once $this->get_plugin_dir() . 'api.php';

		// Third Party
		new AC_ThirdParty_ACF();
		new AC_ThirdParty_NinjaForms();
		new AC_ThirdParty_WooCommerce();
		new AC_ThirdParty_WPML();

		// Hooks
		add_action( 'init', array( $this, 'localize' ) );
		add_action( 'wp_loaded', array( $this, 'after_setup' ) ); // Setup callback, important to load after set_storage_models
		add_action( 'admin_enqueue_scripts', array( $this, 'scripts' ), 11 );
		add_filter( 'plugin_action_links', array( $this, 'add_settings_link' ), 1, 2 );
		add_filter( 'list_table_primary_column', array( $this, 'set_primary_column' ), 20, 1 );

		// Populating columns
		add_action( 'current_screen', array( $this, 'load_listings_headings_and_values' ) );

		// Includes
		$this->_settings = new AC_Admin();
		$this->_addons = new AC_Addons();
		$this->_upgrade = new AC_Upgrade();
		$this->helper = new AC_Helper();

		new AC_Notice_Review();

		// Set capabilities
		register_activation_hook( __FILE__, array( $this, 'set_capabilities' ) );

		add_action( 'admin_init', array( $this, 'set_capabilities_multisite' ) );
	}

	/**
	 * Auto-load in-accessible properties on demand
	 *
	 * @since NEWVERSION
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function __get( $key ) {
		if ( in_array( $key, array( 'helper' ) ) ) {
			return $this->$key();
		}

		return false;
	}

	/**
	 * @since NEWVERSION
	 * @return string Path to plugin dir
	 */
	public function get_plugin_dir() {
		return plugin_dir_path( __FILE__ );
	}

	/**
	 * @since NEWVERSION
	 */
	public function get_plugin_url() {
		return plugin_dir_url( __FILE__ );
	}

	/**
	 * @since NEWVERSION
	 */
	public function get_version() {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';

		if ( null === $this->version ) {
			$this->version = $this->get_plugin_version( __FILE__ );
		}

		return $this->version;
	}

	/**
	 * @since NEWVERSION
	 */
	public function get_plugin_version( $file ) {
		$plugin = get_plugin_data( $file, false, false );

		return isset( $plugin['Version'] ) ? $plugin['Version'] : false;
	}

	/**
	 * @since NEWVERSION
	 */
	public function get_upgrade_version() {
		return '2.0.0';
	}

	/**
	 * Fire callbacks for admin columns setup completion
	 *
	 * @since 2.2
	 */
	public function after_setup() {

		/**
		 * Fires when Admin Columns is fully loaded
		 * Use this for setting up addon functionality
		 *
		 * @since 2.0
		 *
		 * @param CPAC $cpac_instance Main Admin Columns plugin class instance
		 */
		do_action( 'cac/loaded', $this );

		// Current listings page storage model
		if ( $storage_model = $this->get_current_storage_model() ) {
			do_action( 'cac/loaded_listings_screen', $storage_model );
		}
	}

	/**
	 * @since 2.2
	 * @uses load_plugin_textdomain()
	 */
	public function localize() {
		load_plugin_textdomain( 'codepress-admin-columns', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * @since NEWVERSION
	 */
	public function minified() {
		return defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	}

	/**
	 * @since 2.2.4
	 */
	public function scripts() {

		// Listings screen
		if ( $current_storage_model = $this->get_current_storage_model() ) {

			add_filter( 'admin_body_class', array( $this, 'admin_class' ) );
			add_action( 'admin_head', array( $this, 'admin_scripts' ) );

			$minified = $this->minified();

			$url = $this->get_plugin_url();

			wp_register_script( 'cpac-admin-columns', $url . "assets/js/admin-columns{$minified}.js", array( 'jquery', 'jquery-qtip2' ), $this->get_version() );
			wp_register_script( 'jquery-qtip2', $url . "external/qtip2/jquery.qtip{$minified}.js", array( 'jquery' ), $this->get_version() );
			wp_register_style( 'jquery-qtip2', $url . "external/qtip2/jquery.qtip{$minified}.css", array(), $this->get_version(), 'all' );
			wp_register_style( 'cpac-columns', $url . "assets/css/column{$minified}.css", array(), $this->get_version(), 'all' );

			wp_localize_script( 'cpac-admin-columns', 'AC_Storage_Model', $current_storage_model->get_list_selector() );

			wp_enqueue_script( 'cpac-admin-columns' );
			wp_enqueue_style( 'jquery-qtip2' );
			wp_enqueue_style( 'cpac-columns' );

			/**
			 * @param AC_StorageModel $storage_model
			 */
			do_action( 'ac/enqueue_listings_scripts', $current_storage_model );
		}

		// Settings screen
		else if ( cac_is_setting_screen() ) {
			do_action( 'ac/enqueue_settings_scripts' );
		}
	}

	/**
	 * Add caps for Multisite admins
	 */
	public function set_capabilities_multisite() {
		if ( is_multisite() && current_user_can( 'administrator' ) ) {
			$this->set_capabilities();
		}
	}

	/**
	 * Add capability to administrator to manage admin columns.
	 * You can use the capability 'manage_admin_columns' to grant other roles this privilege as well.
	 *
	 * @since 2.0.4
	 */
	public function set_capabilities() {
		if ( $role = get_role( 'administrator' ) ) {
			$role->add_cap( 'manage_admin_columns' );
		}
	}

	/**
	 * Set the primary columns for the Admin Columns columns. Used to place the actions bar.
	 *
	 * @since 2.5.5
	 */
	public function set_primary_column( $default ) {
		if ( $storage_model = $this->get_current_storage_model() ) {
			if ( ! $storage_model->get_column_by_name( $default ) ) {
				$default = key( $storage_model->get_columns() );
			}
		}

		return $default;
	}

	/**
	 * Disable side wide notices
	 *
	 * @return bool True when no notices should be displayed
	 */
	public function suppress_site_wide_notices() {
		return apply_filters( 'ac/suppress_site_wide_notices', false );
	}

	/**
	 * Get registered storage models
	 *
	 * @since 2.5
	 * @return AC_StorageModel[]
	 */
	public function get_storage_models() {
		if ( empty( $this->storage_models ) ) {

			$storage_models = array();

			$classes_dir = $this->get_plugin_dir() . 'classes/';

			// @deprecated NEWVERSION
			require_once $classes_dir . 'Deprecated/storage_model.php';

			// Create a storage model per post type
			foreach ( $this->get_post_types() as $post_type ) {

				$storage_model = new AC_StorageModel_Post();
				$storage_model->set_post_type( $post_type );

				$storage_models[ $storage_model->key ] = $storage_model;
			}

			// Create other storage models
			$storage_model = new AC_StorageModel_User();
			$storage_models[ $storage_model->key ] = $storage_model;

			$storage_model = new AC_StorageModel_Media();
			$storage_models[ $storage_model->key ] = $storage_model;

			$storage_model = new AC_StorageModel_Comment();
			$storage_models[ $storage_model->key ] = $storage_model;

			if ( apply_filters( 'pre_option_link_manager_enabled', false ) ) { // as of 3.5 link manager is removed
				$storage_model = new AC_StorageModel_Link();
				$storage_models[ $storage_model->key ] = $storage_model;
			}

			/**
			 * Filter the available storage models
			 * Used by external plugins to add additional storage models
			 *
			 * @since 2.0
			 *
			 * @param array $storage_models List of storage model class instances ( [key] => [AC_StorageModel object], where [key] is the storage key, such as "user", "post" or "my_custom_post_type")
			 * @param object $this CPAC
			 */
			$this->storage_models = apply_filters( 'cac/storage_models', $storage_models, $this );
		}

		return $this->storage_models;
	}

	/**
	 * Retrieve a storage model object based on its key
	 *
	 * @since 2.0
	 *
	 * @param string $key Storage model key (e.g. post, page, wp-users)
	 *
	 * @return bool|AC_StorageModel Storage Model object (or false, on failure)
	 */
	public function get_storage_model( $key ) {
		$models = $this->get_storage_models();

		return isset( $models[ $key ] ) ? $models[ $key ] : false;
	}

	/**
	 * Only set columns on current screens or on specific ajax calls
	 *
	 * @since 2.4.9
	 */
	public function load_listings_headings_and_values() {

		// Listings screen
		$storage_model = $this->get_current_storage_model();

		// WP Ajax calls (not AC)
		if ( $model = cac_wp_is_doing_ajax() ) {
			$storage_model = $this->get_storage_model( $model );
			$storage_model->layouts()->init_listings_layout();
		}

		if ( $storage_model ) {
			$storage_model->init_column_headings();
			$storage_model->init_column_values();
		}
	}

	/**
	 * Get column object
	 *
	 * @since 2.5.4
	 *
	 * @param $storage_key AC_StorageModel->key
	 * @param $layout_id AC_StorageModel->layout
	 * @param $column_name CPAC_Column->name
	 *
	 * @return CPAC_Column
	 */
	public function get_column( $storage_key, $layout_id, $column_name ) {
		$column = false;
		if ( $storage_model = $this->get_storage_model( $storage_key ) ) {
			$storage_model->layouts()->set_layout( $layout_id );
			$column = $storage_model->get_column_by_name( $column_name );
		}

		return $column;
	}

	/**
	 * Get storage model object of currently active storage model
	 * On the users overview page, for example, this returns the AC_StorageModel object
	 *
	 * @since 2.2.4
	 *
	 * @return AC_StorageModel
	 */
	public function get_current_storage_model() {
		if ( null === $this->current_storage_model && $this->get_storage_models() ) {
			foreach ( $this->get_storage_models() as $storage_model ) {
				if ( $storage_model->is_current_screen() ) {
					$storage_model->layouts()->init_listings_layout();
					$this->current_storage_model = $storage_model;
					break;
				}
			}
		}

		return $this->current_storage_model;
	}

	/**
	 * Get a list of post types for which Admin Columns is active
	 *
	 * @since 1.0
	 *
	 * @return array List of post type keys (e.g. post, page)
	 */
	public function get_post_types() {
		$post_types = array();

		if ( post_type_exists( 'post' ) ) {
			$post_types['post'] = 'post';
		}
		if ( post_type_exists( 'page' ) ) {
			$post_types['page'] = 'page';
		}

		$post_types = array_merge( $post_types, get_post_types( array(
			'_builtin' => false,
			'show_ui'  => true,
		) ) );

		/**
		 * Filter the post types for which Admin Columns is active
		 *
		 * @since 2.0
		 *
		 * @param array $post_types List of active post type names
		 */
		return apply_filters( 'cac/post_types', $post_types );
	}

	/**
	 * Get a list of taxonomies supported by Admin Columns
	 *
	 * @since 1.0
	 *
	 * @return array List of taxonomies
	 */
	public function get_taxonomies() {
		$taxonomies = get_taxonomies( array( 'show_ui' => true ) );
		if ( isset( $taxonomies['post_format'] ) ) {
			unset( $taxonomies['post_format'] );
		}

		/**
		 * Filter the post types for which Admin Columns is active
		 *
		 * @since 2.0
		 *
		 * @param array $post_types List of active post type names
		 */
		return apply_filters( 'cac/taxonomies', $taxonomies );
	}

	/**
	 * Add a settings link to the Admin Columns entry in the plugin overview screen
	 *
	 * @since 1.0
	 * @see filter:plugin_action_links
	 */
	public function add_settings_link( $links, $file ) {
		if ( $file != plugin_basename( __FILE__ ) ) {
			return $links;
		}

		array_unshift( $links, '<a href="' . esc_url( admin_url( "options-general.php?page=codepress-admin-columns" ) ) . '">' . __( 'Settings' ) . '</a>' );

		return $links;
	}

	/**
	 * Adds a body class which is used to set individual column widths
	 *
	 * @since 1.4.0
	 *
	 * @param string $classes body classes
	 *
	 * @return string
	 */
	public function admin_class( $classes ) {
		if ( $storage_model = $this->get_current_storage_model() ) {
			$classes .= " cp-{$storage_model->key}";
		}

		return $classes;
	}

	/**
	 * Admin CSS for Column width and Settings Icon
	 *
	 * @since 1.4.0
	 */
	public function admin_scripts() {
		$storage_model = $this->get_current_storage_model();

		if ( ! $storage_model ) {
			return;
		}

		// CSS: columns width
		$css_column_width = false;
		foreach ( $storage_model->get_columns() as $column ) {
			if ( $width = $column->get_width() ) {
				$css_column_width .= ".cp-" . $storage_model->get_key() . " .wrap table th.column-" . $column->get_name() . " { width: " . $width . $column->get_width_unit() . " !important; }";
			}

			// Load external scripts
			$column->scripts();
		}
		?>
		<?php if ( $css_column_width ) : ?>
			<style type="text/css">
				<?php echo $css_column_width; ?>
			</style>
		<?php endif; ?>
		<?php

		// JS: Edit button
		if ( current_user_can( 'manage_admin_columns' ) && '0' !== $this->get_general_option( 'show_edit_button' ) ) : ?>
			<script type="text/javascript">
				jQuery( document ).ready( function() {
					jQuery( '.tablenav.top .actions:last' ).append( '<a href="<?php echo esc_url( $storage_model->get_edit_link() ); ?>" class="cpac-edit add-new-h2"><?php _e( 'Edit columns', 'codepress-admin-columns' ); ?></a>' );
				} );
			</script>
		<?php endif; ?>

		<?php

		/**
		 * Add header scripts that only apply to column screens.
		 * @since 2.3.5
		 *
		 * @param object CPAC Main Class
		 */
		do_action( 'cac/admin_head', $storage_model, $this );
	}

	public function get_first_storage_model_key() {
		$keys = array_keys( (array) $this->get_storage_models() );

		return array_shift( $keys );
	}

	public function get_first_storage_model() {
		$models = array_values( $this->get_storage_models() );

		return isset( $models[0] ) ? $models[0] : false;
	}

	/**
	 * @since 2.5
	 */
	public function use_delete_confirmation() {
		return apply_filters( 'ac/delete_confirmation', true );
	}

	/**
	 * Whether this request is a columns screen (i.e. a content overview page)
	 *
	 * @since 2.2
	 * @return bool Returns true if the current screen is a columns screen, false otherwise
	 */
	public function is_columns_screen() {
		$storage_model = $this->get_current_storage_model();

		$is_column_screen = $storage_model && $storage_model->is_current_screen();

		/**
		 * Filter whether the current screen is a columns screen (i.e. a content overview page)
		 * Useful for advanced used with custom content overview pages
		 *
		 * @since 2.2
		 *
		 * @param bool $columns_screen Whether the current request is a columns screen
		 */
		return apply_filters( 'cac/is_columns_screen', $is_column_screen );
	}

	/**
	 * Whether the current screen is the Admin Columns settings screen
	 *
	 * @since 2.2
	 *
	 * @param string $tab Specifies a tab screen (optional)
	 *
	 * @return bool True if the current screen is the settings screen, false otherwise
	 */
	public function is_settings_screen( $tab = '' ) {
		return cac_is_setting_screen( $tab );
	}

	/**
	 * Whether the current screen is a screen in which Admin Columns is used
	 * Used to quickly check whether storage models should be loaded
	 *
	 * @since 2.2
	 * @return bool Whether the current screen is an Admin Columns screen
	 */
	public function is_cac_screen() {

		/**
		 * Filter whether the current screen is a screen in which Admin Columns is active
		 *
		 * @since 2.2
		 *
		 * @param bool $is_cac_screen Whether the current screen is an Admin Columns screen
		 */
		return apply_filters( 'cac/is_cac_screen', $this->is_columns_screen() || cac_is_doing_ajax() || $this->is_settings_screen() );
	}

	/**
	 * Get admin columns settings class instance
	 *
	 * @since 2.2
	 * @return AC_Admin Settings class instance
	 */
	public function settings() {
		return $this->_settings;
	}

	/**
	 * Get admin columns add-ons class instance
	 *
	 * @since 2.2
	 * @return AC_Addons Add-ons class instance
	 */
	public function addons() {
		return $this->_addons;
	}

	/**
	 * Get admin columns upgrade class instance
	 *
	 * @since 2.2.7
	 * @return AC_Upgrade Upgrade class instance
	 */
	public function upgrade() {
		return $this->_upgrade;
	}

	/**
	 * @since NEWVERSION
	 *
	 * @return AC_Helper
	 */
	public function helper() {
		return $this->helper;
	}

	/**
	 * Check whether the Advanced Custom Fields plugin is active
	 *
	 * @since 2.4.9
	 *
	 * @return bool Whether the Advanced Custom Fields plugin is active
	 */
	public function is_plugin_acf_active() {
		return cpac_is_acf_active();
	}

	/**
	 * Check whether the WooCommerce plugin is active
	 *
	 * @since 2.4.9
	 *
	 * @return bool Whether the WooCommerce plugin is active
	 */
	public function is_plugin_woocommerce_active() {
		return cpac_is_woocommerce_active();
	}

	/**
	 * @since 2.1.1
	 */
	public function get_general_option( $option ) {
		if ( null === $this->general_options ) {
			$this->general_options = get_option( 'cpac_general_options' );
		}

		return isset( $this->general_options[ $option ] ) ? $this->general_options[ $option ] : false;
	}

}

// @deprecated since NEWVERSION
function cpac() {
	return AC();
}

// @since NEWVERSION
function AC() {
	return CPAC::instance();
}

// Global for backwards compatibility.
$GLOBALS['cpac'] = AC();