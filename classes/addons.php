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

		$addons = array(
			'cac-addon-sortable'	=> (object) array(
				'title'		=> __( 'Sortable Columns' ),
				'intro'		=> "
					<p>By default WordPress let's you only sort by title, date and comments.</p>
					<p>This will let you sort any added column.</p>
				",
				'content'	=> "
					<p>This will make all of the new columns support sorting.</p>
					<p>By default WordPress let's you sort by title, date, comments and author. This will make you be able to <strong>sort by any column of any type!</strong></p>
					<p>Perfect for sorting your articles, media files, comments, links and users.</p>
					<p class='description'>(columns that are added by other plugins are not supported)</p>
				"
			),
			'cac-addon-filtering'	=> (object) array(
				'title'		=> __( 'Filtering Columns' ),
				'intro'		=> "
					<p>By default WordPress let's you only sort by title, date and comments.</p>
					<p>This will let you sort any added column.</p>
				",
				'content'	=> "
					<p>This will make all of the new columns support sorting.</p>
					<p>By default WordPress let's you sort by title, date, comments and author. This will make you be able to <strong>sort by any column of any type!</strong></p>
					<p>Perfect for sorting your articles, media files, comments, links and users.</p>
					<p class='description'>(columns that are added by other plugins are not supported)</p>
				"
			),
			'cac-addon-new'	=> (object) array(
				'title'		=> __( 'New Columns' ),
				'intro'		=> "
					<p>By default WordPress let's you only sort by title, date and comments.</p>
					<p>This will let you sort any added column.</p>
				",
				'content'	=> "
					<p>This will make all of the new columns support sorting.</p>
					<p>By default WordPress let's you sort by title, date, comments and author. This will make you be able to <strong>sort by any column of any type!</strong></p>
					<p>Perfect for sorting your articles, media files, comments, links and users.</p>
					<p class='description'>(columns that are added by other plugins are not supported)</p>
				"
			),
		);

		foreach ( $addons as $id => $addon ) :
		?>
			<li class="cpac-pointer <?php echo $id; ?>" rel="cpac-addon-instructions-<?php echo $id; ?>">
				<h3><?php echo $addon->title; ?></h3>
				<?php echo $addon->intro; ?>
				<a href="http://www.admincolumns.com/?addon=<?php echo $id; ?>" class="button">Get the addon</a>
				<span class="state"><?php _e( 'active', 'cpac' ); ?></span>

				<div id="cpac-addon-instructions-<?php echo $id; ?>" style="display:none;">
					<h3><?php echo $addon->title; ?></h3>
					<?php echo $addon->content; ?>
				</div>
			</li>

		<?php
		endforeach;
		return;

		$feed = get_transient( 'cpac_addons_feed' );

		if ( ! $feed ) {

			$feed = '<div class="error"><p>' . __( 'There was an error retrieving the extensions list from the server. Please try again later.', 'cpac' ) . '</div>';

			$remote = wp_remote_get( 'http://www.admincolumns.com/?feed=extensions', array( 'sslverify' => false ) );
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
						<a href="http://admincolumns.com/addons/?ref=1" class="button-primary" title="<?php _e( 'Browse All Add-ons', 'edd' ); ?>" target="_blank"><?php _e( 'Browse all Add-ons', 'cpac' ); ?></a>
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