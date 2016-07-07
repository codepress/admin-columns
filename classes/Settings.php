<?php
defined( 'ABSPATH' ) or die();

/**
 * CPAC_Settings Class
 *
 * @since 2.0
 */
class AC_Settings {

	CONST OPTION_CURRENT = 'cpac_current_model';

	/**
	 * Settings Page
	 *
	 * @since 2.0
	 */
	private $settings_page;

	/**
	 * @since 2.0
	 *
	 * @param object CPAC
	 */
	function __construct() {

		add_action( 'admin_menu', array( $this, 'settings_menu' ) );

		// handle requests gets a low priority so it will trigger when all other plugins have loaded their columns
		add_action( 'admin_init', array( $this, 'handle_column_request' ), 1000 );

		add_action( 'wp_ajax_cpac_column_refresh', array( $this, 'ajax_column_refresh' ) );
		add_action( 'wp_ajax_cpac_columns_update', array( $this, 'ajax_columns_update' ) );

		add_action( 'cpac_messages', array( $this, 'maybe_display_addon_statuschange_message' ) );
	}

	/**
	 * @since 3.1.1
	 */
	public function get_settings_page() {
		return $this->settings_page;
	}

	/**
	 * Get available Admin Columns admin page URLs
	 *
	 * @since 2.2
	 * @return array Available settings URLs ([settings_page] => [url])
	 */
	public function get_settings_urls() {

		$settings_urls = array(
			'admin'            => admin_url( 'options-general.php?page=codepress-admin-columns' ),
			'settings'         => admin_url( 'options-general.php?page=codepress-admin-columns&tab=settings' ),
			'network_settings' => network_admin_url( 'settings.php?page=codepress-admin-columns' ),
			'info'             => admin_url( 'options-general.php?page=codepress-admin-columns&info=' ),
			'upgrade'          => admin_url( 'options-general.php?page=cpac-upgrade' ),
		);

		return $settings_urls;
	}

	/**
	 * Get the settings URL for a page
	 *
	 * @since 2.2
	 *
	 * @param string $page Optional. Admin page to get the URL from. Defaults to the basic Admin Columns page
	 *
	 * @return string Settings page URL
	 */
	public function get_settings_url( $page = '' ) {
		$settings_urls = $this->get_settings_urls();

		if ( isset( $settings_urls[ $page ] ) ) {
			return $settings_urls[ $page ];
		}

		if ( ! $page ) {
			return $settings_urls['admin'];
		}

		return add_query_arg( 'tab', $page, $this->get_settings_url() );
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
	 * @since 2.2
	 */
	public function ajax_column_refresh() {
		check_ajax_referer( 'cpac-settings' );

		if ( ! current_user_can( 'manage_admin_columns' ) ) {
			wp_die();
		}

		$column_name = filter_input( INPUT_POST, 'column' );

		if ( empty( $_POST['formdata'] ) || ! $column_name || ! isset( $_POST['layout'] ) ) {
			wp_die();
		}

		parse_str( $_POST['formdata'], $formdata );

		if ( empty( $formdata['cpac_key'] ) ) {
			wp_die();
		}

		$storage_model = cpac()->get_storage_model( $formdata['cpac_key'] );

		if ( ! $storage_model ) {
			wp_die();
		}

		$storage_model->set_layout( $_POST['layout'] );

		if ( empty( $formdata[ $storage_model->key ][ $column_name ] ) ) {
			wp_die();
		}

		$columndata = $formdata[ $storage_model->key ][ $column_name ];

		$column = $storage_model->create_column_instance( $columndata['type'] );

		if ( ! $column ) {
			wp_die();
		}

		$storage_model->populate_column_options( $column, $columndata );

		ob_start();
		$this->display_column( $column );
		wp_send_json_success( ob_get_clean() );
	}

	/**
	 * @since 1.0
	 */
	public function settings_menu() {
		$this->settings_page = add_submenu_page( 'options-general.php', __( 'Admin Columns Settings', 'codepress-admin-columns' ), __( 'Admin Columns', 'codepress-admin-columns' ), 'manage_admin_columns', 'codepress-admin-columns', array( $this, 'display' ) );

		register_setting( 'cpac-general-settings', 'cpac_general_options' );

		add_filter( 'option_page_capability_cpac-general-settings', array( $this, 'add_capability' ) );
		add_action( "load-{$this->settings_page}", array( $this, 'help_tabs' ) );

		$this->enqueue_admin_scripts();
	}

	/**
	 * Print scripts and styles
	 *
	 * @since 2.4.7
	 */
	public function enqueue_admin_scripts() {
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
		$minified = cpac()->minified();

		wp_enqueue_style( 'wp-pointer' );
		wp_enqueue_style( 'cpac-admin', cpac()->get_plugin_url() . "assets/css/admin-column{$minified}.css", array(), cpac()->get_version(), 'all' );
	}

	/**
	 * @since 1.0
	 */
	public function admin_scripts() {

		// width slider
		wp_enqueue_style( 'jquery-ui-lightness', cpac()->get_plugin_url() . 'assets/ui-theme/jquery-ui-1.8.18.custom.css', array(), cpac()->get_version(), 'all' );
		wp_enqueue_script( 'jquery-ui-slider' );

		$minified = cpac()->minified();

		wp_enqueue_script( 'cpac-admin-settings', cpac()->get_plugin_url() . "assets/js/admin-settings{$minified}.js", array(
			'jquery',
			'dashboard',
			'jquery-ui-slider',
			'jquery-ui-sortable',
			'wp-pointer',
		), cpac()->get_version() );

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
	 * @since 2.5
	 */
	public function ajax_columns_update() {
		check_ajax_referer( 'cpac-settings' );

		if ( ! current_user_can( 'manage_admin_columns' ) ) {
			wp_die();
		}

		$storage_model = cpac()->get_storage_model( filter_input( INPUT_POST, 'storage_model' ) );

		if ( ! $storage_model ) {
			wp_die();
		}

		$storage_model->set_layout( filter_input( INPUT_POST, 'layout' ) );

		parse_str( $_POST['data'], $formdata );

		if ( ! isset( $formdata[ $storage_model->key ] ) ) {
			wp_send_json_error( array(
					'type'    => 'error',
					'message' => __( 'You need at least one column', 'codepress-admin-columns' ),
				)
			);
		}

		$stored = $storage_model->store( $formdata[ $storage_model->key ] );

		if ( is_wp_error( $stored ) ) {
			wp_send_json_error( array(
					'type'    => 'same-settings' === $stored->get_error_code() ? 'notice notice-warning' : 'error',
					'message' => $stored->get_error_message(),
				)
			);
		}

		wp_send_json_success(
			sprintf( __( 'Settings for %s updated successfully.', 'codepress-admin-columns' ), "<strong>" . esc_html( $storage_model->get_label_or_layout_name() ) . "</strong>" )
			. ' <a href="' . esc_attr( $storage_model->get_link() ) . '">' . esc_html( sprintf( __( 'View %s screen', 'codepress-admin-columns' ), $storage_model->label ) ) . '</a>'
		);
	}

	/**
	 * @since 1.0
	 */
	public function handle_column_request() {
		if ( ! isset( $_REQUEST['cpac_action'] ) || ! isset( $_REQUEST['_cpac_nonce'] ) || ! cac_is_setting_screen() || ! current_user_can( 'manage_admin_columns' ) ) {
			return;
		}

		switch ( $_REQUEST['cpac_action'] ) :

			case 'restore_by_type' :
				$key = isset( $_REQUEST['cpac_key'] ) ? $_REQUEST['cpac_key'] : '';

				if ( wp_verify_nonce( $_REQUEST['_cpac_nonce'], 'restore-type' ) && $key ) {
					if ( $storage_model = cpac()->get_storage_model( $key ) ) {

						if ( isset( $_POST['cpac_layout'] ) ) {
							$storage_model->set_layout( $_POST['cpac_layout'] );
						}

						$storage_model->restore();
						$storage_model->flush_columns();

						cpac_settings_message( sprintf( __( 'Settings for %s restored successfully.', 'codepress-admin-columns' ), "<strong>" . $storage_model->get_label_or_layout_name() . "</strong>" ), 'updated' );
					}
				}
				break;

			case 'restore_all' :
				if ( wp_verify_nonce( $_REQUEST['_cpac_nonce'], 'restore-all' ) ) {
					$this->restore_all();
				}
				break;

		endswitch;
	}

	/**
	 * Restore all column defaults
	 *
	 * @since 1.0
	 */
	private function restore_all() {
		global $wpdb;
		$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE 'cpac_options_%'" );
		$wpdb->query( "DELETE FROM {$wpdb->options} WHERE option_name LIKE 'cpac_layouts%'" );

		cpac_admin_message( __( 'Default settings succesfully restored.', 'codepress-admin-columns' ), 'updated' );
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
	 *
	 * @param string $storage_model URL type.
	 *
	 * @return string Url.
	 */
	public function get_url( $type ) {
		$urls = array(
			'pricing'       => ac_get_site_url( 'pricing-purchase' ),
			'documentation' => ac_get_site_url( 'documentation' ),
		);

		return isset( $urls[ $type ] ) ? $urls[ $type ] : false;
	}

	/**
	 * Welcome screen
	 *
	 * @since 2.0
	 */
	public function welcome_screen() {

		// Should only be set after upgrade
		$show_welcome = false !== get_transient( 'cpac_show_welcome' );

		// Should only be set manual
		if ( isset( $_GET['info'] ) ) {
			$show_welcome = true;
		}

		if ( ! $show_welcome ) {
			return false;
		}

		// Set check that welcome should not be displayed.
		delete_transient( 'cpac_show_welcome' );

		$tab = ! empty( $_GET['info'] ) ? $_GET['info'] : 'whats-new';

		?>

		<div id="cpac-welcome" class="wrap about-wrap">

			<h1><?php _e( "Welcome to Admin Columns", 'codepress-admin-columns' ); ?><?php echo cpac()->get_version(); ?></h1>

			<div class="about-text">
				<?php _e( "Thank you for updating to the latest version!", 'codepress-admin-columns' ); ?>
				<?php _e( "Admin Columns is more polished and enjoyable than ever before. We hope you like it.", 'codepress-admin-columns' ); ?>
			</div>

			<div class="cpac-content-body">
				<h2 class="nav-tab-wrapper">
					<a class="cpac-tab-toggle nav-tab <?php if ( $tab == 'whats-new' ) {
						echo 'nav-tab-active';
					} ?>" href="<?php echo $this->get_settings_url( 'info' ); ?>whats-new"><?php _e( "What’s New", 'codepress-admin-columns' ); ?></a>
					<a class="cpac-tab-toggle nav-tab <?php if ( $tab == 'changelog' ) {
						echo 'nav-tab-active';
					} ?>" href="<?php echo $this->get_settings_url( 'info' ); ?>changelog"><?php _e( "Changelog", 'codepress-admin-columns' ); ?></a>
				</h2>

				<?php if ( 'whats-new' === $tab ) : ?>

					<h3><?php _e( "Important", 'codepress-admin-columns' ); ?></h3>

					<h4><?php _e( "Database Changes", 'codepress-admin-columns' ); ?></h4>
					<p><?php _e( "The database has been changed between versions 1 and 2. But we made sure you can still roll back to version 1x without any issues.", 'codepress-admin-columns' ); ?></p>

					<?php if ( get_option( 'cpac_version', false ) < cpac()->get_upgrade_version() ) : ?>
						<p><?php _e( "Make sure you backup your database and then click", 'codepress-admin-columns' ); ?>
							<a href="<?php echo $this->get_settings_url( 'upgrade' ); ?>" class="button-primary"><?php _e( "Upgrade Database", 'codepress-admin-columns' ); ?></a>
						</p>
					<?php endif; ?>

					<h4><?php _e( "Potential Issues", 'codepress-admin-columns' ); ?></h4>
					<p><?php _e( "Do to the sizable refactoring the code, surounding Addons and action/filters, your website may not operate correctly. It is important that you read the full", 'codepress-admin-columns' ); ?>
						<a href="<?php ac_site_url(); ?>migrating-from-v1-to-v2" target="_blank"><?php _e( "Migrating from v1 to v2", 'codepress-admin-columns' ); ?></a> <?php _e( "guide to view the full list of changes.", 'codepress-admin-columns' ); ?> <?php printf( __( "When you have found a bug please <a href='%s'>report them to us</a> so we can fix it in the next release.", 'codepress-admin-columns' ), 'mailto:info@codepress.nl' ); ?>
					</p>

					<div class="cpac-alert cpac-alert-error">
						<p>
							<strong><?php _e( "Important!", 'codepress-admin-columns' ); ?></strong> <?php _e( "If you updated the Admin Columns plugin without prior knowledge of such changes, Please roll back to the latest", 'codepress-admin-columns' ); ?>
							<a href="http://downloads.wordpress.org/plugin/codepress-admin-columns.1.4.9.zip"> <?php _e( "version 1", 'codepress-admin-columns' ); ?></a> <?php _e( "of this plugin.", 'codepress-admin-columns' ); ?>
						</p>
					</div>

				<?php endif; ?>
				<?php if ( 'changelog' === $tab ) : ?>

					<h3><?php _e( "Changelog for", 'codepress-admin-columns' ); ?><?php echo cpac()->get_version(); ?></h3>
					<?php

					$items = file_get_contents( cpac()->get_plugin_dir() . 'readme.txt' );
					$items = explode( '= ' . cpac()->get_version() . ' =', $items );
					$items = end( $items );
					$items = current( explode( "\n\n", $items ) );
					$items = current( explode( "= ", $items ) );
					$items = array_filter( array_map( 'trim', explode( "*", $items ) ) );

					?>
					<ul class="cpac-changelog">
						<?php foreach ( $items as $item ) :
							$item = explode( 'http', $item );
							?>
							<li><?php echo $item[0]; ?><?php if ( isset( $item[1] ) ): ?><a
									href="http<?php echo $item[1]; ?>"
									target="_blank"><?php _e( "Learn more", 'codepress-admin-columns' ); ?></a><?php endif; ?>
							</li>
						<?php endforeach; ?>
					</ul>

				<?php endif; ?>
				<hr/>

			</div><!--.cpac-content-body-->

			<div class="cpac-content-footer">
				<a class="button-primary button-large" href="<?php echo $this->get_settings_url( 'general' ); ?>"><?php _e( "Start using Admin Columns", 'codepress-admin-columns' ); ?></a>
			</div><!--.cpac-content-footer-->

		</div>
		<?php

		return true;
	}

	/**
	 * @since 1.0
	 */
	public function display_settings() { ?>
		<table class="form-table cpac-form-table settings">
			<tbody>
			<tr class="general">
				<th scope="row">
					<h3><?php _e( 'General Settings', 'codepress-admin-columns' ); ?></h3>
					<p><?php _e( 'Customize your Admin Columns settings.', 'codepress-admin-columns' ); ?></p>
				</th>
				<td class="padding-22">
					<div class="cpac_general">
						<form method="post" action="options.php">
							<?php settings_fields( 'cpac-general-settings' ); ?>
							<?php $options = get_option( 'cpac_general_options' ); ?>
							<p>
								<label for="show_edit_button">
									<input name="cpac_general_options[show_edit_button]" type="hidden" value="0">
									<input name="cpac_general_options[show_edit_button]" id="show_edit_button" type="checkbox" value="1" <?php checked( ! isset( $options['show_edit_button'] ) || ( '1' == $options['show_edit_button'] ) ); ?>>
									<?php _e( "Show \"Edit Columns\" button on admin screens. Default is <code>on</code>.", 'codepress-admin-columns' ); ?>
								</label>
							</p>

							<?php do_action( 'cac/settings/general', $options ); ?>

							<p>
								<input type="submit" class="button" value="<?php _e( 'Save' ); ?>"/>
							</p>
						</form>
					</div>
				</td>
			</tr><!--.general-->

			<?php

			/** Allow plugins to add their own custom settings to the settings page. */
			if ( $groups = apply_filters( 'cac/settings/groups', array() ) ) {

				foreach ( $groups as $id => $group ) {

					$title = isset( $group['title'] ) ? $group['title'] : '';
					$description = isset( $group['description'] ) ? $group['description'] : '';

					?>
					<tr>
						<th scope="row">
							<h3><?php echo $title; ?></h3>

							<p><?php echo $description; ?></p>
						</th>
						<td class="padding-22">
							<?php

							/** Use this Hook to add additional fields to the group */
							do_action( "cac/settings/groups/row={$id}" );

							?>
						</td>
					</tr>
					<?php
				}
			}
			?>

			<tr class="restore">
				<th scope="row">
					<h3><?php _e( 'Restore Settings', 'codepress-admin-columns' ); ?></h3>
					<p><?php _e( 'This will delete all column settings and restore the default settings.', 'codepress-admin-columns' ); ?></p>
				</th>
				<td class="padding-22">
					<form method="post">
						<?php wp_nonce_field( 'restore-all', '_cpac_nonce' ); ?>
						<input type="hidden" name="cpac_action" value="restore_all"/>
						<input type="submit" class="button" name="cpac-restore-defaults" value="<?php _e( 'Restore default settings', 'codepress-admin-columns' ) ?>" onclick="return confirm('<?php _e( "Warning! ALL saved admin columns data will be deleted. This cannot be undone. \'OK\' to delete, \'Cancel\' to stop", 'codepress-admin-columns' ); ?>');"/>
					</form>
				</td>
			</tr><!--.restore-->

			</tbody>
		</table>

		<?php
	}

	public function sort_by_label( $a, $b ) {
		return strcmp( $a->label, $b->label );
	}

	private function set_user_model_preference( $storage_model_key ) {
		update_user_meta( get_current_user_id(), self::OPTION_CURRENT, $storage_model_key );
	}

	private function get_user_model_preference() {
		return cpac()->get_storage_model( get_user_meta( get_current_user_id(), self::OPTION_CURRENT, true ) );
	}

	public function get_settings_storage_model() {

		if ( isset( $_REQUEST['cpac_key'] ) ) {

			// By request
			if ( $_storage_model = cpac()->get_storage_model( $_REQUEST['cpac_key'] ) ) {
				$storage_model = $_storage_model;
			}

			// User preference
			else if ( $_storage_model = $this->get_user_model_preference() ) {
				$storage_model = $_storage_model;
			}

			// First one served
			else {
				$storage_model = cpac()->get_first_storage_model();
			}

			$this->set_user_model_preference( $storage_model->key );
		}

		else {

			// User preference
			if ( $exists = $this->get_user_model_preference() ) {
				$storage_model = $exists;
			}

			// First one served
			else {
				$storage_model = cpac()->get_first_storage_model();
			}
		}

		// Init layout
		$storage_model->init_settings_layout();

		return $storage_model;
	}

	public function messages() {
		if ( ! empty( $GLOBALS['cpac_settings_messages'] ) ) {
			echo implode( $GLOBALS['cpac_settings_messages'] );
		}
	}

	/**
	 * @since 1.0
	 */
	public function display() {

		if ( $this->welcome_screen() ) {
			return;
		}

		$tabs = array(
			'general'  => __( 'Admin Columns', 'codepress-admin-columns' ),
			'settings' => __( 'Settings', 'codepress-admin-columns' ),
			'addons'   => __( 'Add-ons', 'codepress-admin-columns' ),
		);

		/**
		 * Filter the tabs on the settings screen
		 *
		 * @param array $tabs Available tabs
		 */
		$tabs = apply_filters( 'cac/settings/tabs', $tabs );

		$current_tab = ( empty( $_GET['tab'] ) ) ? 'general' : sanitize_text_field( urldecode( $_GET['tab'] ) );
		?>
		<div id="cpac" class="wrap">
			<h2 class="nav-tab-wrapper cpac-nav-tab-wrapper">
				<?php foreach ( $tabs as $name => $label ) : ?>
					<a href="<?php echo esc_attr( $this->get_settings_url( 'admin' ) . "&tab=" . $name ); ?>" class="nav-tab<?php echo $current_tab == $name ? ' nav-tab-active' : ''; ?>"><?php echo esc_html( $label ); ?></a>
				<?php endforeach; ?>
			</h2>

			<?php do_action( 'cpac_messages' ); ?>

			<?php
			switch ( $current_tab ) :
				case 'general':

					$storage_model = $this->get_settings_storage_model();
					$has_been_stored = $storage_model->get_stored_columns() ? true : false;

					// columns should not be editable when layout isn't
					if ( $layout = $storage_model->get_layout_object() ) {
						if ( isset( $layout->not_editable ) ) {
							$storage_model->enable_php_export();
						}
					}

					// Grouped storage models
					$grouped = array();
					foreach ( cpac()->get_storage_models() as $_storage_model ) {
						$grouped[ $_storage_model->get_menu_type() ][] = (object) array(
							'key'   => $_storage_model->key,
							'link'  => $_storage_model->settings_url(),
							'label' => $_storage_model->label,
						);
						usort( $grouped[ $_storage_model->get_menu_type() ], array( $this, 'sort_by_label' ) );
					}
					?>

					<?php do_action( 'cac/settings/after_menu' ); ?>

					<div class="columns-container<?php echo $has_been_stored ? ' stored' : ''; ?>" data-type="<?php echo esc_attr( $storage_model->key ); ?>" data-layout="<?php echo esc_attr( $storage_model->get_layout() ); ?>">
						<div class="main">
							<div class="menu">
								<select title="Select type" id="cpac_storage_modal_select">
									<?php foreach ( $grouped as $menu_type => $models ) : ?>
										<optgroup label="<?php echo esc_attr( $menu_type ); ?>">
											<?php foreach ( $models as $model ) : ?>
												<option value="<?php echo esc_attr( $model->link ); ?>" <?php selected( $model->key, $storage_model->key ); ?>><?php echo esc_html( $model->label ); ?></option>
											<?php endforeach; ?>
										</optgroup>
									<?php endforeach; ?>
								</select>
								<span class="spinner"></span>

								<?php $storage_model->screen_link(); ?>
							</div>

							<?php do_action( 'cac/settings/after_title', $storage_model ); ?>

						</div>

						<div class="columns-right">
							<div class="columns-right-inside">
								<?php if ( ! $storage_model->is_using_php_export() ) : ?>
									<div class="sidebox form-actions">
										<?php $label = __( 'Store settings', 'codepress-admin-columns' ); ?>
										<h3>
											<span class="left"><?php echo esc_html( $label ); ?></span>
											<?php if ( 18 > strlen( $label ) && ( $truncated_label = $storage_model->get_truncated_side_label( $label ) ) ) : ?>
												<span class="right contenttype"><?php echo esc_html( $truncated_label ); ?></span>
											<?php else : ?>
												<span class="clear contenttype"><?php echo esc_html( $storage_model->label ); ?></span>
											<?php endif; ?>
										</h3>

										<div class="form-update">
											<a href="javascript:;" class="button-primary submit update"><?php _e( 'Update' ); ?></a>
											<a href="javascript:;" class="button-primary submit save"><?php _e( 'Save' ); ?></a>
										</div>

										<form class="form-reset" method="post">
											<input type="hidden" name="cpac_key" value="<?php echo esc_attr( $storage_model->key ); ?>"/>
											<input type="hidden" name="cpac_action" value="restore_by_type"/>
											<input type="hidden" name="cpac_layout" value="<?php echo esc_attr( $storage_model->layout ); ?>"/>
											<?php wp_nonce_field( 'restore-type', '_cpac_nonce' ); ?>

											<?php $onclick = cpac()->use_delete_confirmation() ? ' onclick="return confirm(\'' . esc_attr( addslashes( sprintf( __( "Warning! The %s columns data will be deleted. This cannot be undone. 'OK' to delete, 'Cancel' to stop", 'codepress-admin-columns' ), "'" . $storage_model->get_label_or_layout_name() . "'" ) ) ) . '\');"' : ''; ?>
											<input class="reset-column-type" type="submit"<?php echo $onclick; ?> value="<?php _e( 'Restore columns', 'codepress-admin-columns' ); ?>">
											<span class="spinner"></span>
										</form>

										<?php do_action( 'cac/settings/form_actions', $storage_model ); ?>

									</div><!--form-actions-->
								<?php endif; ?>

								<?php do_action( 'cac/settings/sidebox', $storage_model ); ?>

								<?php if ( ! cpac_is_pro_active() ) : ?>

									<?php $url_args = array(
										'utm_source'   => 'plugin-installation',
										'utm_medium'   => 'banner',
										'utm_campaign' => 'plugin-installation',
									); ?>
									<div class="sidebox" id="pro-version">
										<div class="padding-box cta">
											<h3>
												<a href="<?php echo add_query_arg( array_merge( $url_args, array( 'utm_content' => 'title' ) ), ac_get_site_url() ); ?>"><?php _e( 'Get Admin Columns Pro', 'codepress-admin-columns' ) ?></a>
											</h3>

											<div class="inside">
												<ul>
													<li>
														<a href="<?php echo add_query_arg( array_merge( $url_args, array( 'utm_content' => 'usp-sorting' ) ), ac_get_site_url() . '/upgrade-to-admin-columns-pro/' ) ?>"><?php _e( 'Add Sorting', 'codepress-admin-columns' ); ?></a>
													</li>
													<li>
														<a href="<?php echo add_query_arg( array_merge( $url_args, array( 'utm_content' => 'usp-filtering' ) ), ac_get_site_url() . '/upgrade-to-admin-columns-pro/' ) ?>"><?php _e( 'Add Filtering', 'codepress-admin-columns' ); ?></a>
													</li>
													<li>
														<a href="<?php echo add_query_arg( array_merge( $url_args, array( 'utm_content' => 'usp-import-export' ) ), ac_get_site_url() . '/upgrade-to-admin-columns-pro/' ) ?>"><?php _e( 'Add Import/Export', 'codepress-admin-columns' ); ?></a>
													</li>
													<li>
														<a href="<?php echo add_query_arg( array_merge( $url_args, array( 'utm_content' => 'usp-editing' ) ), ac_get_site_url() . '/upgrade-to-admin-columns-pro/' ) ?>"><?php _e( 'Add Inline Edit', 'codepress-admin-columns' ); ?></a>
													</li>
													<li>
														<a href="<?php echo add_query_arg( array_merge( $url_args, array( 'utm_content' => 'usp-columns-sets' ) ), ac_get_site_url() . '/upgrade-to-admin-columns-pro/' ) ?>"><?php _e( 'Multiple Column Sets', 'codepress-admin-columns' ); ?></a>
													</li>
												</ul>
												<p>
													<?php printf( __( "Check out <a href='%s'>Admin Columns Pro</a> for more details!", 'codepress-admin-columns' ), add_query_arg( array_merge( $url_args, array( 'utm_content' => 'cta' ) ), ac_get_site_url() ) ); ?>
												</p>
											</div>
										</div>
									</div>

									<div class="sidebox" id="direct-feedback">
										<div id="feedback-choice">
											<h3><?php _e( 'Are you happy with Admin Columns?', 'codepress-admin-columns' ); ?></h3>

											<div class="inside">
												<a href="#" class="yes">Yes</a>
												<a href="#" class="no">No</a>
											</div>
										</div>
										<div id="feedback-support">
											<div class="inside">
												<p><?php _e( "What's wrong? Need help? Let us know!", 'codepress-admin-columns' ); ?></p>

												<p><?php _e( 'Check out our extensive documentation, or you can open a support topic on WordPress.org!', 'codepress-admin-columns' ); ?></p>
												<ul class="share">
													<li>
														<a href="<?php echo add_query_arg( array(
															'utm_source'   => 'plugin-installation',
															'utm_medium'   => 'feedback-docs-button',
															'utm_campaign' => 'plugin-installation',
														), $this->get_url( 'documentation' ) ); ?>" target="_blank">
															<div class="dashicons dashicons-editor-help"></div> <?php _e( 'Docs', 'codepress-admin-columns' ); ?>
														</a>
													</li>
													<li>
														<a href="https://wordpress.org/support/plugin/codepress-admin-columns"
															target="_blank">
															<div class="dashicons dashicons-wordpress"></div> <?php _e( 'Forums', 'codepress-admin-columns' ); ?>
														</a>
													</li>
												</ul>
												<div class="clear"></div>
											</div>
										</div>
										<div id="feedback-rate">
											<div class="inside">
												<p><?php _e( "Woohoo! We're glad to hear that!", 'codepress-admin-columns' ); ?></p>

												<p><?php _e( 'We would really love it if you could show your appreciation by giving us a rating on WordPress.org or tweet about Admin Columns!', 'codepress-admin-columns' ); ?></p>
												<ul class="share">
													<li>
														<a href="http://wordpress.org/support/view/plugin-reviews/codepress-admin-columns#postform" target="_blank">
															<div class="dashicons dashicons-star-empty"></div> <?php _e( 'Rate', 'codepress-admin-columns' ); ?>
														</a>
													</li>

													<li>
														<a href="<?php echo add_query_arg( array(
															'hashtags' => 'admincolumns',
															'text'     => urlencode( "I'm using Admin Columns for WordPress!" ),
															'url'      => urlencode( 'http://wordpress.org/plugins/codepress-admin-columns/' ),
															'via'      => 'wpcolumns',
														), 'https://twitter.com/intent/tweet' ); ?>" target="_blank">
															<div class="dashicons dashicons-twitter"></div> <?php _e( 'Tweet', 'codepress-admin-columns' ); ?>
														</a>
													</li>

													<li>
														<a href="<?php echo add_query_arg( array(
															'utm_source'   => 'plugin-installation',
															'utm_medium'   => 'feedback-purchase-button',
															'utm_campaign' => 'plugin-installation',
														), ac_get_site_url() ); ?>" target="_blank">
															<div class="dashicons dashicons-cart"></div> <?php _e( 'Buy Pro', 'codepress-admin-columns' ); ?>
														</a>
													</li>
												</ul>
												<div class="clear"></div>
											</div>
										</div>
									</div>

								<?php endif; ?>

								<div class="sidebox" id="plugin-support">
									<h3><?php _e( 'Support', 'codepress-admin-columns' ); ?></h3>

									<div class="inside">
										<?php if ( version_compare( get_bloginfo( 'version' ), '3.2', '>' ) ) : ?>
											<p><?php _e( 'Check the <strong>Help</strong> section in the top-right screen.', 'codepress-admin-columns' ); ?></p>
										<?php endif; ?>
										<p>
											<?php printf( __( "For full documentation, bug reports, feature suggestions and other tips <a href='%s'>visit the Admin Columns website</a>", 'codepress-admin-columns' ), $this->get_url( 'documentation' ) ); ?>
										</p>
									</div>
								</div><!--plugin-support-->

							</div><!--.columns-right-inside-->
						</div><!--.columns-right-->

						<div class="columns-left">
							<?php if ( ! $storage_model->get_default_stored_columns() && ! $storage_model->is_using_php_export() ): ?>
								<div class="cpac-notice">
									<p>
										<?php echo sprintf( __( 'Please visit the %s screen once to load all available columns', 'codepress-admin-columns' ), "<a href='" . $storage_model->get_link() . "'>" . esc_html( $storage_model->label ) . "</a>" ); ?>
									</p>
								</div>
							<?php endif ?>

							<?php $this->messages(); ?>

							<div class="ajax-message"><p></p></div>

							<?php if ( $storage_model->is_using_php_export() ) : ?>
								<div class="notice notice-warning below-h2">
									<p><?php printf( __( 'The columns for %s are set up via PHP and can therefore not be edited', 'codepress-admin-columns' ), '<strong>' . esc_html( $storage_model->label ) . '</strong>' ); ?></p>
								</div>
							<?php endif; ?>

							<div class="cpac-boxes<?php echo esc_attr( $storage_model->is_using_php_export() ? ' disabled' : '' ); ?>">

								<div class="cpac-columns">
									<form method="post" action="<?php echo esc_attr( $storage_model->get_edit_link() ); ?>">

										<input type="hidden" name="cpac_key" value="<?php echo esc_attr( $storage_model->key ); ?>"/>
										<input type="hidden" name="cpac_action" value="update_by_type"/>
										<input type="hidden" name="cpac_layout" value="<?php echo esc_attr( $storage_model->layout ); ?>"/>

										<?php do_action( 'cac/settings/form_columns', $storage_model ); ?>

										<?php wp_nonce_field( 'update-type', '_cpac_nonce' ); ?>

										<?php
										foreach ( $storage_model->get_columns() as $column ) {
											$this->display_column( $column );
										}
										?>
									</form>

								</div><!--.cpac-columns-->

								<div class="column-footer">
									<?php if ( ! $storage_model->is_using_php_export() ) : ?>
										<div class="order-message">
											<?php _e( 'Drag and drop to reorder', 'codepress-admin-columns' ); ?>
										</div>
										<div class="button-container">
											<?php if ( apply_filters( 'ac/settings/enable_clear_columns_button', false ) ) : ?>
												<a href="javascript:;" class="clear_columns" data-clear-columns><?php _e( 'Clear all columns ', 'codepress-admin-columns' ) ?></a>
											<?php endif; ?>
											<a href="javascript:;" class="add_column button-primary">+ <?php _e( 'Add Column', 'codepress-admin-columns' ); ?></a>
											<?php /*<a href="javascript:;" class="button-primary submit update"><?php _e( 'Update' ); ?></a>*/ ?>
											<?php /*<a href="javascript:;" class="button-primary submit save"><?php _e( 'Save' ); ?></a>*/ ?>
										</div>
									<?php endif; ?>
								</div><!--.cpac-column-footer-->

							</div><!--.cpac-boxes-->

							<?php do_action( 'cac/settings/after_columns', $storage_model ); ?>

						</div><!--.columns-left-->
						<div class="clear"></div>

						<div class="for-cloning-only" style="display:none">
							<?php
							foreach ( $storage_model->get_column_types() as $column ) {
								$this->display_column( $column );
							}
							?>
						</div>

					</div><!--.columns-container-->

					<div class="clear"></div>
					<?php
					break;
				case 'settings' :
					$this->display_settings();
					break;
				case 'addons' :
					$this->tab_addons();
					break;
				default:

					/**
					 * Action to add tab contents
					 *
					 */
					do_action( 'cac/settings/tab_contents/tab=' . $current_tab );

			endswitch;
			?>
		</div><!--.wrap-->
		<?php
	}

	/**
	 * @since 2.2
	 */
	public function tab_addons() {

		$addon_groups = cpac()->addons()->get_addon_groups();
		$grouped_addons = cpac()->addons()->get_available_addons( true );
		?>
		<?php foreach ( $grouped_addons as $group_name => $addons ) : ?>
			<h3><?php echo $addon_groups[ $group_name ]; ?></h3>

			<ul class="cpac-addons">
				<?php foreach ( $addons as $addon_name => $addon ) : ?>
					<li>
						<div class="cpac-addon-content">
							<?php if ( ! empty( $addon['image'] ) ) : ?>
								<img src="<?php echo esc_attr( $addon['image'] ); ?>"/>
							<?php else : ?>
								<h3><?php echo esc_html( $addon['title'] ); ?></h3>
							<?php endif; ?>
						</div>
						<div class="cpac-addon-header">
							<h3><?php echo esc_html( $addon['title'] ); ?></h3>
							<p><?php echo esc_html( $addon['description'] ); ?></p>
						</div>
						<div class="cpac-addon-actions">
							<?php

							// Installed..
							if ( $plugin_basename = cpac()->addons()->get_installed_addon_plugin_basename( $addon_name ) ) : ?>
								<?php if ( is_plugin_active( $plugin_basename ) ) : ?>
									<?php $deactivation_url = wp_nonce_url( add_query_arg( array( 'action' => 'deactivate', 'plugin' => urlencode( $plugin_basename ), 'cpac-redirect' => true, ), admin_url( 'plugins.php' ) ), 'deactivate-plugin_' . $plugin_basename ); ?>
									<a href="#" class="button button-disabled cpac-installed"><?php _e( 'Active', 'codepress-admin-columns' ); ?></a>
									<a href="<?php echo esc_attr( $deactivation_url ); ?>" class="button right"><?php _e( 'Deactivate', 'codepress-admin-columns' ); ?></a>
								<?php else : ?>
									<?php $activation_url = wp_nonce_url( add_query_arg( array( 'action' => 'activate', 'plugin' => urlencode( $plugin_basename ), 'cpac-redirect' => true, ), admin_url( 'plugins.php' ) ), 'activate-plugin_' . $plugin_basename ); ?>
									<a href="#" class="button button-disabled cpac-installed"><?php _e( 'Installed', 'codepress-admin-columns' ); ?></a>
									<a href="<?php echo esc_attr( $activation_url ); ?>" class="button right"><?php _e( 'Activate', 'codepress-admin-columns' ); ?></a>
								<?php endif; ?>
								<?php

							// Not installed...
							else :

								if ( cpac_is_pro_active() ) :
									$install_url = wp_nonce_url( add_query_arg( array( 'action' => 'install', 'plugin' => $addon_name ), $this->get_settings_url( 'addons' ) ), 'install-cac-addon' );
									?>
									<a href="<?php echo esc_attr( $install_url ); ?>" class="button"><?php _e( 'Download & Install', 'codepress-admin-columns' ); ?></a>
									<?php
								else : ?>
									<a target="_blank" href="<?php echo esc_attr( $this->get_url( 'pricing' ) ); ?>" class="button"><?php _e( 'Get this add-on', 'codepress-admin-columns' ); ?></a>
								<?php endif; ?>
							<?php endif; ?>
						</div>
					</li>
				<?php endforeach; // addons ?>
			</ul>
		<?php endforeach; // grouped_addons
	}

	/**
	 * @since 2.0
	 */
	private function display_column( CPAC_Column $column ) {

		$classes = implode( ' ', array_filter( array( "cpac-box-" . $column->get_type(), $column->get_property( 'classes' ) ) ) );
		?>
		<div class="cpac-column <?php echo $classes; ?>" data-type="<?php echo $column->get_type(); ?>"<?php echo $column->get_property( 'is_cloneable' ) ? ' data-clone="' . $column->get_property( 'clone' ) . '"' : ''; ?> data-default="<?php echo $column->is_default(); ?>">
			<input type="hidden" class="column-name" name="<?php $column->attr_name( 'column-name' ); ?>" value="<?php echo esc_attr( $column->get_name() ); ?>"/>
			<input type="hidden" class="type" name="<?php $column->attr_name( 'type' ); ?>" value="<?php echo $column->get_type(); ?>"/>
			<input type="hidden" class="clone" name="<?php $column->attr_name( 'clone' ); ?>" value="<?php echo $column->get_property( 'clone' ); ?>"/>

			<div class="column-meta">
				<table class="widefat">
					<tbody>
					<tr>
						<td class="column_sort">
							<span class="cpacicon-move"></span>
						</td>
						<td class="column_label">
							<div class="inner">
								<div class="meta">

									<span title="<?php echo esc_attr( __( 'width', 'codepress-admin-columns' ) ); ?>" class="width" data-indicator-id="">
										<?php echo $column->get_option( 'width' ) ? $column->get_option( 'width' ) . $column->get_option( 'width_unit' ) : ''; ?>
									</span>

									<?php
									/**
									 * Fires in the meta-element for column options, which is displayed right after the column label
									 *
									 * @since 2.0
									 *
									 * @param CPAC_Column $column_instance Column class instance
									 */
									do_action( 'cac/column/settings_meta', $column );

									/**
									 * @deprecated 2.2 Use cac/column/settings_meta instead
									 */
									do_action( 'cac/column/label', $column );
									?>

								</div>
								<a class="toggle" href="javascript:;"><?php echo stripslashes( $column->get_label() ); ?></a>
								<a class="edit-button" href="javascript:;"><?php _e( 'Edit', 'codepress-admin-columns' ); ?></a>
								<a class="close-button" href="javascript:;"><?php _e( 'Close', 'codepress-admin-columns' ); ?></a>
								<?php if ( $column->get_property( 'is_cloneable' ) ) : ?>
									<a class="clone-button" href="#"><?php _e( 'Clone', 'codepress-admin-columns' ); ?></a>
								<?php endif; ?>
								<a class="remove-button" href="javascript:;"><?php _e( 'Remove', 'codepress-admin-columns' ); ?></a>
							</div>
						</td>
						<td class="column_type">
							<div class="inner">
								<a href="#"><?php echo stripslashes( $column->get_type_label() ); ?></a>
							</div>
						</td>
						<td class="column_edit">
						</td>
					</tr>
					</tbody>
				</table>
			</div><!--.column-meta-->

			<div class="column-form">
				<table class="widefat">
					<tbody>

					<?php
					$column->form_field( array(
						'type'            => 'select',
						'name'            => 'type',
						'label'           => __( 'Type', 'codepress-admin-columns' ),
						'description'     => __( 'Choose a column type.', 'codepress-admin-columns' ) . '<em>' . __( 'Type', 'codepress-admin-columns' ) . ': ' . $column->get_type() . '</em><em>' . __( 'Name', 'codepress-admin-columns' ) . ': ' . $column->get_name() . '</em>',
						'grouped_options' => $column->get_storage_model()->get_grouped_columns(),
						'default'         => $column->get_type(),
					) );

					$column->form_field( array(
						'type'        => 'text',
						'name'        => 'label',
						'placeholder' => $column->get_type_label(),
						'label'       => __( 'Label', 'codepress-admin-columns' ),
						'description' => __( 'This is the name which will appear as the column header.', 'codepress-admin-columns' ),
						'hidden'      => $column->get_property( 'hide_label' ),
					) );

					$column->form_field( array(
						'type'  => 'width',
						'name'  => 'width',
						'label' => __( 'Width', 'codepress-admin-columns' ),
					) );

					/**
					 * Fires directly before the custom options for a column are displayed in the column form
					 *
					 * @since 2.0
					 *
					 * @param CPAC_Column $column_instance Column class instance
					 */
					do_action( 'cac/column/settings_before', $column );
					?>

					<?php
					/**
					 * Load specific column settings.
					 *
					 */
					$column->display_settings();

					?>

					<?php
					/**
					 * Fires directly after the custom options for a column are displayed in the column form
					 *
					 * @since 2.0
					 *
					 * @param CPAC_Column $column_instance Column class instance
					 */
					do_action( 'cac/column/settings_after', $column );
					?>

					<tr class="column_action section">
						<td class="label"></td>
						<td class="input">
							<p>
								<a href="#" class="close-button"><?php _e( 'Close', 'codepress-admin-columns' ); ?></a>
								<?php if ( $column->get_property( 'is_cloneable' ) ) : ?>
									<a class="clone-button" href="#"><?php _e( 'Clone', 'codepress-admin-columns' ); ?></a>
								<?php endif; ?>
								<a href="#" class="remove-button"><?php _e( 'Remove' ); ?></a>
							</p>
						</td>
					</tr>

					</tbody>
				</table>
			</div><!--.column-form-->
		</div><!--.cpac-column-->
		<?php
	}

}