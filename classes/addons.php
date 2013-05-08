<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * CPAC_Addons Class
 *
 * @since 2.0.0
 */
class CPAC_Addons {

	function __construct() {

		add_action( 'admin_menu', array( $this, 'settings_menu' ), 30 );
	}

	/**
	 * Admin Menu.
	 *
	 * Create the admin menu link for the settings page.
	 *
	 * @since 2.0.0
	 */
	public function settings_menu() {

		$page = add_submenu_page( 'codepress-admin-columns', __( 'Add-ons', 'cpac' ), __( 'Add-ons', 'cpac' ), 'manage_options', 'cpac-addons',	array( $this, 'display' ) );

		add_action( "admin_print_styles-{$page}", array( $this, 'admin_styles' ) );
		add_action( "admin_print_scripts-{$page}", array( $this, 'admin_scripts' ) );
	}

	/**
	 * Admin styles
	 *
	 * @since 2.0.0
	 */
	 function admin_styles() {
		wp_enqueue_style( 'wp-pointer' );
		wp_enqueue_style( 'cpac-admin', CPAC_URL . 'assets/css/admin-column.css', array(), CPAC_VERSION, 'all' );
	 }

	 /**
	 * Scripts
	 *
	 * @since 2.0.0
	 */
	 function admin_scripts() {
		wp_enqueue_script( 'wp-pointer' );
		wp_enqueue_script( 'cpac-admin-columns', CPAC_URL . 'assets/js/admin-columns.js', array( 'jquery', 'dashboard', 'jquery-ui-slider', 'jquery-ui-sortable' ), CPAC_VERSION );
	 }

	/**
	 * Add-ons Get Feed
	 *
	 * @since 2.0.0
	 */
	function get_feed() {

		// @todo: uncomment live domain
		$url = 'http://codepress.lan/admincolumns';
		//$url = 'http://www.admincolumns.com';

// @todo
		if ( true || ! $feed = get_transient( 'cpac_addons_feed' ) ) {

			$feed = '<div class="error"><p>' . __( 'There was an error retrieving the extensions list from the server. Please try again later.', 'cpac' ) . '</div>';

			$remote = wp_remote_get( $url . '?addon_feed=extensions', array( 'sslverify' => false ) );
			if ( ! is_wp_error( $remote ) && isset( $remote['body'] ) && strlen( $remote['body'] ) > 0 ) {
				$feed = wp_remote_retrieve_body( $remote );

				set_transient( 'cpac_addons_feed', $feed, 3600 );
			}
		}

		return $feed;
	}

	/**
	 * Display
	 *
	 * @since 2.0.0
	 */
	function display() {
		ob_start();

		?>
		<div id="cpac" class="wrap" >

			<?php screen_icon( 'codepress-admin-columns' ); ?>
			<h2><?php _e( 'Add-ons for Admin Columns', 'cpac' ); ?></h2>

			<table class="form-table">
				<tbody>
					<tr>
					<th scope="row">
						<h3><?php _e( 'Add-ons for Admin Columns', 'cpac' ); ?></h3>
						<p><?php _e( 'These add-ons extend the functionality of Admin Columns.', 'cpac' ); ?></p>
						<a href="http://www.admincolumns.com/addons/?ref=1" class="button-primary" title="<?php _e( 'Browse All Add-ons', 'cpac' ); ?>" target="_blank"><?php _e( 'Browse all Add-ons', 'cpac' ); ?></a>
					</th>
					<td>
						<ul class="addons">
						<?php echo $this->get_feed(); ?>
						</ul>
					</td>
				</tbody>
			</table>
		</div>
		<?php

		echo ob_get_clean();
	}
}