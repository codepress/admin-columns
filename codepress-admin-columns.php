<?php
/*

Plugin Name: 		Codepress Admin Columns
Version: 			2.0.0
Description: 		Customize columns on the administration screens for post(types), pages, media, comments, links and users with an easy to use drag-and-drop interface.
Author: 			Codepress
Author URI: 		http://www.admincolumns.com
Plugin URI: 		http://www.admincolumns.com
Text Domain: 		cpac
Domain Path: 		/languages
License:			GPLv2

Copyright 2011-2013  Codepress  info@codepress.nl

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

// @todo: REMOVE
// /www/plugins/wp-content/plugins/codepress-admin-columns
// /www/plugins/wp-content/plugins/advanced-custom-fields

define( 'CPAC_VERSION', 	 	'2.0.0' ); // current plugin version
define( 'CPAC_UPGRADE_VERSION', '2.0.0' ); // this is the latest version which requires an upgrade
define( 'CPAC_TEXTDOMAIN', 		'codepress-admin-columns' );
define( 'CPAC_URL', 			plugin_dir_url( __FILE__ ) );
define( 'CPAC_DIR', 			plugin_dir_path( __FILE__ ) );

// only run plugin in the admin interface
if ( ! is_admin() )
	return false;

/**
 * Dependencies
 *
 * @since 1.3.0
 */
require_once CPAC_DIR . 'classes/utility.php';
require_once CPAC_DIR . 'classes/third_party.php';
require_once CPAC_DIR . 'classes/deprecated.php';

/**
 * The Codepress Admin Columns Class
 *
 * @since 1.0.0
 *
 */
class CPAC {

	public $storage_models;

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	function __construct() {
		add_action( 'wp_loaded', array( $this, 'init') );
	}

	/**
	 * Initialize plugin.
	 *
	 * Loading sequence is determined and intialized.
	 *
	 * @since 1.0.0
	 */
	public function init() {

		// translations
		load_plugin_textdomain( CPAC_TEXTDOMAIN, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

		// styling & scripts
		add_action( 'admin_enqueue_scripts' , array( $this, 'column_styles') );
		add_filter( 'admin_body_class', array( $this, 'admin_class' ) );
		add_action( 'admin_head', array( $this, 'admin_css') );
		add_action( 'admin_head', array( $this, 'admin_js') );

		// add settings link
		add_filter( 'plugin_action_links',  array( $this, 'add_settings_link'), 1, 2);

		// set storage models
		$this->set_storage_models();

		// ini controllers
		$this->init_controllers();

		/*
		icl_register_string( 'cac2', 'xTitle', 'xTitle' );

		global $sitepress;
		echo $sitepress->get_current_language() . "<br/>\n";
		$t = icl_t( 'cac2', 'xTitle', 'xTitle', $sitepress->get_current_language() );
		echo $t . "!!<br/>\n";

		global $wpdb;
		$res = $wpdb->get_results($wpdb->prepare("
            SELECT s.name, s.value, t.value AS translation_value, t.status
            FROM  {$wpdb->prefix}icl_strings s
            LEFT JOIN {$wpdb->prefix}icl_string_translations t ON s.id = t.string_id
            WHERE s.context = %s
                AND (t.language = %s OR t.language IS NULL)
            ", 'cac2', $sitepress->get_current_language() ), ARRAY_A);

		echo '<pre>'; print_r( $res ); echo '</pre>';
		*/

		// Hook
		do_action( 'cac/loaded', $this );
	}

	/**
	 * Get storage models
	 *
	 * @since 2.0.0
	 *
	 */
	private function set_storage_models() {

		$storage_models = array();

		// include parent and childs
		require_once CPAC_DIR . 'classes/column.php';
		require_once CPAC_DIR . 'classes/storage_model.php';
		require_once CPAC_DIR . 'classes/storage_model/post.php';
		require_once CPAC_DIR . 'classes/storage_model/user.php';
		require_once CPAC_DIR . 'classes/storage_model/media.php';
		require_once CPAC_DIR . 'classes/storage_model/comment.php';
		require_once CPAC_DIR . 'classes/storage_model/link.php';

		// add Posts
		foreach ( $this->get_post_types() as $post_type ) {
			$storage_model = new CPAC_Storage_Model_Post( $post_type );
			$storage_models[ $storage_model->key ] = $storage_model;
		}

		// add User
		$storage_model = new CPAC_Storage_Model_User();
		$storage_models[ $storage_model->key ] = $storage_model;

		// add Media
		$storage_model = new CPAC_Storage_Model_Media();
		$storage_models[ $storage_model->key ] = $storage_model;

		// add Comment
		$storage_model = new CPAC_Storage_Model_Comment();
		$storage_models[ $storage_model->key ] = $storage_model;

		// add Link
		$storage_model = new CPAC_Storage_Model_Link();
		$storage_models[ $storage_model->key ] = $storage_model;

		// Hook to add more models
		do_action( 'cac/storage_models', $storage_models );

		$this->storage_models = $storage_models;
	}

	/**
	 * Get storage model
	 *
	 * @since 2.0.0
	 *
	 * @return array object Storage Model
	 */
	public function get_storage_model( $key ) {

		if ( ! isset( $this->storage_models[ $key ] ) )
			return false;

		return $this->storage_models[ $key ];
	}

	/**
	 * Init controllers
	 *
	 * @since 2.0.0
	 *
	 */
	 function init_controllers() {

		// Settings
		include_once CPAC_DIR . 'classes/settings.php';
		new CPAC_Settings( $this );

		// Upgrade
		require_once CPAC_DIR . 'classes/upgrade.php';
		new CPAC_Upgrade;

		// Addons
		require_once CPAC_DIR . 'classes/addons.php';
		new CPAC_Addons;

		// Export Import
		require_once CPAC_DIR . 'classes/export_import.php';
		new CPAC_Export_Import( $this );
	}

	/**
	 * Get post types - Utility Method
	 *
	 * @since 1.0.0
	 *
	 * @return array Posttypes
	 */
	public function get_post_types() {

		$post_types = array();

		if ( post_type_exists( 'post' ) )
			$post_types['post'] = 'post';

		if ( post_type_exists( 'page' ) )
			$post_types['page'] = 'page';

		$post_types = array_merge( $post_types, get_post_types( array(
			'_builtin' 	=> false,
			'show_ui'	=> true
		)));

		return apply_filters( 'cac/post_types', $post_types );
	}

	/**
	 * Add Settings link to plugin page
	 *
	 * @since 1.0.0
	 *
	 * @param string $links All settings links.
	 * @param string $file Plugin filename.
	 * @return string Link to settings page
	 */
	function add_settings_link( $links, $file ) {

		if ( $file != plugin_basename( __FILE__ ) )
			return $links;

		array_unshift( $links, '<a href="' . admin_url("admin.php") . '?page=codepress-admin-columns">' . __( 'Settings' ) . '</a>' );
		return $links;
	}

	/**
	 * Register column css
	 *
	 * @since 1.0.0
	 */
	public function column_styles() {

		global $pagenow;

		if ( in_array( $pagenow, array( 'edit.php', 'upload.php', 'link-manager.php', 'edit-comments.php', 'users.php' ) ) ) {
			wp_enqueue_style( 'cpac-columns', CPAC_URL . 'assets/css/column.css', array(), CPAC_VERSION, 'all' );
		}
	}

	/**
	 * Admin body class
	 *
	 * Adds a body class which is used to set individual column widths
	 *
	 * @since 1.4.0
	 *
	 * @param string $classes body classes
	 * @return string
	 */
	function admin_class( $classes ) {

		global $current_screen;

		// we dont need the 'edit-' part
		$screen = str_replace( 'edit-', '', $current_screen->id );

		// media library exception
		if ( $current_screen->base == 'upload' && $current_screen->id == 'upload' ) {
			$screen = 'media';
		}

		// link exception
		if ( $current_screen->base == 'link-manager' && $current_screen->id == 'link-manager' ) {
			$screen = 'links';
		}

		// loop the available types
		foreach ( $this->storage_models as $storage_model ) {

			// match against screen or wp-screen
			if ( $storage_model->key == $screen || $storage_model->key == "wp-{$screen}" )
				$classes .= " cp-{$storage_model->key}";
		}

		return $classes;
	}


	/**
	 * Admin CSS for Column width and Settings Icon
	 *
	 * @since 1.4.0
	 */
	function admin_css() {

		global $pagenow;

		// CSS column widths
		$css_column_width = '';

		foreach ( $this->storage_models as $storage_model ) {

			if ( ! $columns = $storage_model->get_stored_columns() )
				continue;

			if ( $storage_model->page !== $pagenow )
				continue;

			foreach ( $columns as $name => $options ) {

				if ( ! empty( $options['width'] ) && is_numeric( $options['width'] ) && $options['width'] > 0 ) {
					$css_column_width .= ".cp-{$storage_model->key} .wrap table th.column-{$name} { width: {$options['width']}% !important; }";
				}
			}
		}

		?>
		<style type="text/css">
			<?php echo $css_column_width; ?>
			#adminmenu #toplevel_page_codepress-admin-columns .wp-menu-image {
				background: transparent url("<?php echo CPAC_URL; ?>assets/images/icon_20.png") no-repeat 6px -24px;
			}
			#adminmenu #toplevel_page_codepress-admin-columns:hover .wp-menu-image,
			#adminmenu #toplevel_page_codepress-admin-columns.wp-menu-open .wp-menu-image {
				background-position: 6px 6px;
			}
			#adminmenu #toplevel_page_codepress-admin-columns a[href="admin.php?page=cpac-upgrade"] {
				display: none;
			}
		</style>
		<?php

	}

	/**
	 * Admin JS
	 *
	 * @since 2.0.0
	 */
	function admin_js() {

		// Will add an active state to addon list.
		if ( $addons = apply_filters( 'cac/addon_list', array() ) ) :

		?>
		<script type="text/javascript">
			jQuery(document).ready( function() {
				<?php foreach ( $addons as $id => $label ) : ?>
				jQuery('#cpac ul.addons').find('.<?php echo $id; ?>').addClass('active').appendTo( '#cpac ul.addons' );
				<?php endforeach; ?>
			});
		</script>

		<?php
		endif;

	}
}

/**
 * Init Class Codepress_Admin_Columns
 *
 * @since 1.0.0
 */
new CPAC();