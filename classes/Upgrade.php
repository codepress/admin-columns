<?php

/**
 * Upgrade
 *
 * Class largely based on code from ACF ( props to Elliot )
 *
 * @since 2.0
 */
class AC_Upgrade {

	public $update_prevented = false;

	/**
	 * @since 2.0
	 */
	function __construct() {

		add_action( 'admin_init', array( $this, 'init' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ), 11 );
		add_action( 'admin_head', array( $this, 'admin_head' ) );
		add_action( 'wp_ajax_cpac_upgrade', array( $this, 'ajax_upgrade' ) );

		if ( ! $this->allow_upgrade() ) {
			add_action( 'cpac_messages', array( $this, 'proaddon_notice' ) );
		}
	}

	/**
	 * Admin CSS to hide upgrade menu and place icon
	 *
	 * @since 2.2.7
	 */
	public function admin_head() { ?>
		<style type="text/css">
			#menu-settings a[href="options-general.php?page=cpac-upgrade"] {
				display: none;
			}
		</style>
		<?php
	}

	/**
	 * Display a notice about the deprecated pro add-on
	 *
	 * @since 2.2
	 */
	public function proaddon_notice() {
		if ( apply_filters( 'cpac/suppress_proaddon_notice', false ) ) {
			return;
		}
		?>
		<div class="message error">
			<p>
				<?php _e( 'The pro add-on is no longer supported. Please login to your account and download Admin Columns Pro', 'codepress-admin-columns' ); ?>
				<a href="<?php ac_site_url( 'pro-addon-information' ); ?>" target="_blank"><?php _e( 'Learn more', 'codepress-admin-columns' ); ?></a>
			</p>
		</div>
		<?php
	}

	/**
	 * Whether upgrading is allowed
	 *
	 * @since 2.1.5
	 *
	 * @return bool Whether plugin upgrading is allowed
	 */
	public function allow_upgrade() {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';

		return ! is_plugin_active( 'cac-addon-pro/cac-addon-pro.php' );
	}

	/**
	 * Add sub menu page & scripts
	 *
	 * @since 2.0
	 */
	public function admin_menu() {

		// Don't run on plugin activate
		if ( isset( $_GET['action'] ) && 'activate-plugin' === $_GET['action'] ) {
			return;
		}

		$upgrade_page = add_submenu_page( 'options-general.php', __( 'Upgrade', 'codepress-admin-columns' ), __( 'Upgrade', 'codepress-admin-columns' ), 'manage_options', 'cpac-upgrade', array( $this, 'start_upgrade' ) );
		add_action( "admin_print_scripts-{$upgrade_page}", array( $this, 'admin_scripts' ) );
	}

	/**
	 * @since 2.0
	 */
	public function init() {

		// @dev_only delete_option( 'cpac_version' ); set_transient( 'cpac_show_welcome', 'display' );
		$version = get_option( 'cpac_version', false );

		// Maybe version pre 2.0.0 was used
		if ( ! $version && get_option( 'cpac_options' ) ) {
			$version = '1.0.0';
		}

		// Maybe upgrade?
		if ( $version ) {

			// run every upgrade
			if ( $version < AC()->get_version() ) {
				// nothing yet
			}

			// run only when updating from v1 to v2
			if ( $version < '2.0.0' ) {

				// show welcome screen
				set_transient( 'cpac_show_welcome', 'display' );
			}

			// run only when database upgrade is needed
			if ( $version < AC()->get_upgrade_version() ) {

				// display upgrade message on every page except upgrade page itself
				if ( ! ( isset( $_REQUEST['page'] ) && 'cpac-upgrade' === $_REQUEST['page'] ) ) {

					$message = __( 'Admin Columns', 'codepress-admin-columns' ) . ' v' . AC()->get_version() . ' ' .
					           __( 'requires a database upgrade', 'codepress-admin-columns' ) .
					           ' (<a class="thickbox" href="' . admin_url() .
					           'plugin-install.php?tab=plugin-information&plugin=codepress-admin-columns&section=changelog&TB_iframe=true&width=640&height=559">' .
					           __( 'why?', 'codepress-admin-columns' ) . '</a>). ' .
					           __( "Please", 'codepress-admin-columns' ) . ' <a href="http://codex.wordpress.org/Backing_Up_Your_Database">' .
					           __( "backup your database", 'codepress-admin-columns' ) . '</a>, ' .
					           __( "then click", 'codepress-admin-columns' ) . ' <a href="' . admin_url() . 'options-general.php?page=cpac-upgrade" class="button">' .
					           __( "Upgrade Database", 'codepress-admin-columns' ) . '</a>';

					cpac_admin_message( $message, 'updated' );
				}
			}

			// run when NO upgrade is needed
			elseif ( $version < AC()->get_version() ) {

				update_option( 'cpac_version', AC()->get_version() );
			}
		}

		// Fresh install
		else {

			update_option( 'cpac_version', AC()->get_version() );
		}
	}

	/**
	 * Init Upgrade Process
	 *
	 * @since 2.0
	 */
	public function ajax_upgrade() {

		// vars
		$return = array(
			'status'  => false,
			'message' => "",
			'next'    => false,
		);

		$version = $_POST['version'];

		// versions
		switch ( $version ) {

			case '2.0.0' :

				$old_settings = get_option( 'cpac_options' );

				// old settings
				if ( ! empty( $old_settings['columns'] ) ) {

					foreach ( $old_settings['columns'] as $storage_key => $old_columns ) {

						$columns = array();

						if ( $old_columns ) {

							// used to determine clone ID
							$tax_count = null;
							$post_count = null;
							$meta_count = null;

							foreach ( $old_columns as $old_column_name => $old_column_settings ) {

								// only active columns
								if ( isset( $old_column_settings['state'] ) && 'on' !== $old_column_settings['state'] ) {
									continue;
								}

								// convert old settings to new
								$settings = array_merge( $old_column_settings, array(
									'type'  => $old_column_name,
									'clone' => ''
								) );

								// set name
								$name = $old_column_name;

								// convert: Users
								if ( 'wp-users' == $storage_key ) {

									// is user post count?
									if ( strpos( $old_column_name, 'column-user_postcount-' ) !== false ) {
										$settings['type'] = 'column-user_postcount';
										$settings['clone'] = $post_count;
										$settings['post_type'] = str_replace( 'column-user_postcount-', '', $old_column_name );

										$name = $post_count ? $settings['type'] . '-' . $settings['clone'] : $settings['type'];
										$post_count++;
									}
								}

								// convert: Media
								elseif ( 'wp-media' == $storage_key ) {

									if ( 'column-filesize' == $old_column_name ) {
										$name = 'column-file_size';
										$settings['type'] = $name;
									}
									// is EXIF data?
									elseif ( strpos( $old_column_name, 'column-image-' ) !== false ) {
										$name = 'column-exif_data';
										$settings['type'] = $name;
										$settings['exif_datatype'] = str_replace( 'column-image-', '', $old_column_name );
									}
									elseif ( 'column-file_paths' == $old_column_name ) {
										$name = 'column-available_sizes';
										$settings['type'] = $name;
									}
								}

								// convert: Comments
								elseif ( 'wp-comments' == $storage_key ) {

									if ( 'column-author_author' == $old_column_name ) {
										$name = 'column-author';
										$settings['type'] = $name;
									}
								}

								// convert: Posts
								else {

									if ( 'column-attachment-count' == $old_column_name ) {
										$name = 'column-attachment_count';
										$settings['type'] = $name;
									}
									elseif ( 'column-author-name' == $old_column_name ) {
										$name = 'column-author_name';
										$settings['type'] = $name;
										$settings['display_author_as'] = $old_column_settings['display_as'];
									}
									elseif ( 'column-before-moretag' == $old_column_name ) {
										$name = 'column-before_moretag';
										$settings['type'] = $name;
									}
									elseif ( 'column-comment-count' == $old_column_name ) {
										$name = 'column-comment_count';
										$settings['type'] = $name;
										$settings['comment_status'] = 'total_comments';
									}
									elseif ( 'column-comment-status' == $old_column_name ) {
										$name = 'column-comment_status';
										$settings['type'] = $name;
									}
									elseif ( 'column-ping-status' == $old_column_name ) {
										$name = 'column-ping_status';
										$settings['type'] = $name;
									}
									elseif ( 'column-page-slug' == $old_column_name ) {
										$name = 'column-slug';
										$settings['type'] = $name;
									}
									elseif ( 'column-page-template' == $old_column_name ) {
										$name = 'column-page_template';
										$settings['type'] = $name;
									}
								}

								// convert: Applies to all storage types

								// is taxonomy?
								if ( strpos( $old_column_name, 'column-taxonomy-' ) !== false ) {
									$settings['type'] = 'column-taxonomy';
									$settings['clone'] = $tax_count;
									$settings['taxonomy'] = str_replace( 'column-taxonomy-', '', $old_column_name );

									$name = $tax_count ? $settings['type'] . '-' . $settings['clone'] : $settings['type'];
									$tax_count++;
								}
								// is custom field?
								elseif ( strpos( $old_column_name, 'column-meta-' ) !== false ) {

									$settings['type'] = 'column-meta';
									//$settings['clone'] = str_replace( 'column-meta-', '', $old_column_name );
									$settings['clone'] = $meta_count;

									$name = $meta_count ? $settings['type'] . '-' . $settings['clone'] : $settings['type'];
									$meta_count++;
								}
								elseif ( 'column-word-count' == $old_column_name ) {
									$name = 'column-word_count';
									$settings['type'] = $name;
								}

								// add to column set
								$columns[ $name ] = $settings;

								// reorder so that active column are at the top of the pile.
								$active = $inactive = array();
								foreach ( $columns as $name => $_settings ) {
									if ( 'on' === $_settings['state'] ) {
										$active[ $name ] = $_settings;
									}
									else {
										$inactive[ $name ] = $_settings;
									}
								}
								$columns = array_merge( $active, $inactive );
							}

							// store column settings
							if ( ! get_option( "cpac_options_{$storage_key}" ) ) {
								update_option( "cpac_options_{$storage_key}", $columns );
							}
						}
					}
				}

				// update version
				update_option( 'cpac_version', $version );

				$return = array(
					'status'  => true,
					'message' => __( "Migrating Column Settings", 'codepress-admin-columns' ) . '...',
					'next'    => false,
				);

				break;
		}

		// return json
		echo json_encode( $return );
		die;
	}

	/*
	* Starting points of the upgrade process
	*
	* @since 2.0
	*/
	public function start_upgrade() {

		$version = get_option( 'cpac_version', '1.0.0' );
		$next = false;

		// list of starting points
		if ( $version < '2.0.0' ) {
			$next = '2.0.0';
		}

		// Run upgrade?
		if ( $next ) : ?>
			<script type="text/javascript">
				run_upgrade( "<?php echo $next; ?>" );
			</script>
			<?php

		// No update required
		else : ?>
			<p><?php _e( 'No Upgrade Required', 'codepress-admin-columns' ); ?></p>
			<a href="<?php echo admin_url( 'options-general.php' ); ?>?page=codepress-admin-columns&amp;info"><?php _e( 'Return to welcome screen.', 'codepress-admin-columns' ); ?></a>
			<?php
		endif;
	}

	/**
	 * Scripts
	 *
	 * @since 2.0
	 */
	public function admin_scripts() {
		wp_enqueue_script( 'cpac-upgrade', AC()->get_plugin_url() . 'assets/js/upgrade.js', array( 'jquery' ), AC()->get_version() );
		wp_enqueue_style( 'cpac-admin', AC()->get_plugin_url() . 'assets/css/admin-column.css', array(), AC()->get_version(), 'all' );

		// javascript translations
		wp_localize_script( 'cpac-upgrade', 'cpac_upgrade_i18n', array(
			'complete'    => __( 'Upgrade Complete!', 'codepress-admin-columns' ) . '</p><p><a href="' . admin_url( 'options-general.php' ) . '?page=codepress-admin-columns&info">' . __( 'Return to settings.', 'codepress-admin-columns' ) . "</a>",
			'error'       => __( 'Error', 'codepress-admin-columns' ),
			'major_error' => __( 'Sorry. Something went wrong during the upgrade process. Please report this on the support forum.', 'codepress-admin-columns' )
		) );
	}
}