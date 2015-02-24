<?php

/**
 * CPAC_Settings Class
 *
 * @since 2.0
 */
class CPAC_Settings {

	/**
	 * CPAC class
	 *
	 * @since 2.0
	 */
	private $cpac;

	/**
	 * Settings Page
	 *
	 * @since 2.0
	 */
	private $settings_page;

	/**
	 * @since 2.0
	 * @param object CPAC
	 */
	function __construct( $cpac ) {

		$this->cpac = $cpac;

		// register settings
		add_action( 'admin_menu', array( $this, 'settings_menu' ) );

		// handle requests gets a low priority so it will trigger when all other plugins have loaded their columns
		add_action( 'admin_init', array( $this, 'handle_column_request' ), 1000 );

		add_action( 'wp_ajax_cpac_column_refresh', array( $this, 'ajax_column_refresh' ) );

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

		/**
		 * Filter the URLs for the different settings screens available in admin columns
		 *
		 * @since 2.2
		 *
		 * @param array $settings_urls Available settings URLs ([settings_page] => [url])
		 * @param CPAC_Settings $settings_instance Settings class instance
		 */
		$settings_urls = apply_filters( 'cac/settings/settings_urls', array(
			'admin' 		=> admin_url( 'options-general.php?page=codepress-admin-columns' ),
			'info' 			=> admin_url( 'options-general.php?page=codepress-admin-columns&info=' ),
			'upgrade' 		=> admin_url( 'options-general.php?page=cpac-upgrade' )
		), $this );

		return $settings_urls;
	}

	/**
	 * Get the settings URL for a page
	 *
	 * @since 2.2
	 * @param string $page Optional. Admin page to get the URL from. Defaults to the basic Admin Columns page
	 * @return string Settings page URL
	 */
	public function get_settings_url( $page = '' ) {

		$settings_urls = $this->get_settings_urls();

		if ( isset( $settings_urls[ $page ] ) ) {
			return $settings_urls[ $page ];
		}

		if ( ! $page ) {
			return $settings_urls['admin'];;
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
			$message = __( 'Add-on successfully activated.', 'cpac' );
		}
		else if ( ! empty( $_REQUEST['deactivate'] ) ) {
			$message = __( 'Add-on successfully deactivated.', 'cpac' );
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
		if ( ! empty( $_POST['formdata'] ) && ! empty( $_POST['column'] ) ) {
			parse_str( $_POST['formdata'], $formdata );
			$storagemodel_key = ! empty( $formdata['cpac_key'] ) ? $formdata['cpac_key'] : '';

			if ( $storagemodel_key && ! empty( $formdata[ $storagemodel_key ][ $_POST['column'] ] ) ) {
				$columndata = $formdata[ $formdata['cpac_key'] ][ $_POST['column'] ];
				$storage_model = $this->cpac->get_storage_model( $formdata['cpac_key'] );
				$registered_columns = $storage_model->get_registered_columns();

				if ( in_array( $columndata['type'], array_keys( $registered_columns ) ) ) {
					$column = clone $registered_columns[ $columndata['type'] ];
					$column->set_clone( $columndata['clone'] );

					foreach ( $columndata as $optionname => $optionvalue ) {
						$column->set_options( $optionname, $optionvalue );
					}

					$column->sanitize_label();

					$columns = array( $column->properties->name => $column );

					do_action( 'cac/columns', $columns );
					do_action( "cac/columns/storage_key={$storagemodel_key}", $columns );

					$column->display();
				}
			}
		}

		exit;
	}

	/**
	 * @since 1.0
	 */
	public function settings_menu() {

		// add settings page
		$this->settings_page = add_submenu_page( 'options-general.php', __( 'Admin Columns Settings', 'cpac' ), __( 'Admin Columns', 'cpac' ), 'manage_admin_columns', 'codepress-admin-columns', array( $this, 'display' ), false, 98 );

		// add help tabs
		add_action( "load-{$this->settings_page}", array( $this, 'help_tabs' ) );

		// add scripts & styles
		add_action( "admin_print_styles-{$this->settings_page}", array( $this, 'admin_styles' ) );
		add_action( "admin_print_scripts-{$this->settings_page}", array( $this, 'admin_scripts' ) );

		// register setting
		register_setting( 'cpac-general-settings', 'cpac_general_options' );

		// add cap to options.php
   		add_filter( 'option_page_capability_cpac-general-settings', array( $this, 'add_capability' ) );
	}

	/**
	 * Allows the capaiblity 'manage_admin_columns' to store data through /wp-admin/options.php
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
		wp_enqueue_style( 'wp-pointer' );
		wp_enqueue_style( 'jquery-ui-lightness', CPAC_URL . 'assets/ui-theme/jquery-ui-1.8.18.custom.css', array(), CPAC_VERSION, 'all' );
		wp_enqueue_style( 'cpac-admin', CPAC_URL . 'assets/css/admin-column.css', array(), CPAC_VERSION, 'all' );
	}

	/**
	 * @since 1.0
	 */
	public function admin_scripts() {

		wp_enqueue_script( 'wp-pointer' );
		wp_enqueue_script( 'jquery-ui-slider' );

		$minified = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

		wp_enqueue_script( 'cpac-admin-settings', CPAC_URL . "assets/js/admin-settings{$minified}.js", array( 'jquery', 'dashboard', 'jquery-ui-slider', 'jquery-ui-sortable' ), CPAC_VERSION );

		// javascript translations
		wp_localize_script( 'cpac-admin-settings', 'cpac_i18n', array(
			'clone'	=> __( '%s column is already present and can not be duplicated.', 'cpac' ),
		));
	}

	/**
	 * @since 1.0
	 */
	public function handle_column_request() {

		// only handle updates from the admin columns page
		if ( ! ( isset($_GET['page'] ) && in_array( $_GET['page'], array( 'codepress-admin-columns' ) ) && isset( $_REQUEST['cpac_action'] ) ) ) {
			return false;
		}

		// use $_REQUEST because the values are send both over $_GET and $_POST
		$action = isset( $_REQUEST['cpac_action'] ) ? $_REQUEST['cpac_action'] 	: '';
		$nonce  = isset( $_REQUEST['_cpac_nonce'] ) ? $_REQUEST['_cpac_nonce'] 	: '';
		$key 	= isset( $_REQUEST['cpac_key'] ) 	? $_REQUEST['cpac_key'] 	: '';

		switch ( $action ) :

			case 'update_by_type' :
				if ( wp_verify_nonce( $nonce, 'update-type' ) ) {
					$storage_model = $this->cpac->get_storage_model( $key );
					$storage_model->store();
				}
				break;

			case 'restore_by_type' :
				if ( wp_verify_nonce( $nonce, 'restore-type' ) ) {
					$storage_model = $this->cpac->get_storage_model( $key );
					$storage_model->restore();
				}
				break;

			case 'restore_all' :
				if ( wp_verify_nonce( $nonce, 'restore-all' ) ) {
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

		cpac_admin_message( __( 'Default settings succesfully restored.',  'cpac' ), 'updated' );
	}

	/**
	 * Add help tabs to top menu
	 *
	 * @since 1.3.0
	 */
	public function help_tabs() {

		$screen = get_current_screen();

		if ( ! method_exists( $screen,'add_help_tab' ) )
			return;

		$tabs = array(
			array(
				'title'		=> __( "Overview", 'cpac' ),
				'content'	=>
					"<h5>Admin Columns</h5>
					<p>". __( "This plugin is for adding and removing additional columns to the administration screens for post(types), pages, media library, comments, links and users. Change the column's label and reorder them.", 'cpac' ) . "</p>"
			),
			array(
				'title'		=> __( "Basics", 'cpac' ),
				'content'	=> "
					<h5>" 	. __( "Change order", 'cpac' ) . "</h5>
					<p>" 	. __( "By dragging the columns you can change the order which they will appear in.", 'cpac' ) . "</p>
					<h5>" 	. __( "Change label", 'cpac' ) . "</h5>
					<p>" 	. __( "By clicking on the triangle you will see the column options. Here you can change each label of the columns heading.", 'cpac' ) . "</p>
					<h5>" 	. __( "Change column width", 'cpac' ) . "</h5>
					<p>" 	. __( "By clicking on the triangle you will see the column options. By using the draggable slider you can set the width of the columns in percentages.", 'cpac' ) . "</p>
				"
			),
			array(
				'title'		=> __( "Custom Field", 'cpac' ),
				'content'	=>
					"<h5>". __( "'Custom Field' column", 'cpac' ) . "</h5>
					<p>". __( "The custom field colum uses the custom fields from posts and users. There are 10 types which you can set.", 'cpac' ) . "</p>
					<ul>
						<li><strong>". __( "Default", 'cpac' ) . "</strong><br/>". __( "Value: Can be either a string or array. Arrays will be flattened and values are seperated by a ',' comma.", 'cpac' ) . "</li>
						<li><strong>". __( "Image", 'cpac' ) . "</strong><br/>". __( "Value: should contain an image URL or Attachment IDs ( seperated by a ',' comma ).", 'cpac' ) . "</li>
						<li><strong>". __( "Excerpt", 'cpac' ) . "</strong><br/>". __( "Value: This will show the first 20 words of the Post content.", 'cpac' ) . "</li>
						<li><strong>". __( "Multiple Values", 'cpac' ) . "</strong><br/>". __( "Value: should be an array. This will flatten any ( multi dimensional ) array.", 'cpac' ) . "</li>
						<li><strong>". __( "Numeric", 'cpac' ) . "</strong><br/>". __( "Value: Integers only.<br/>If you have the 'sorting addon' this will be used for sorting, so you can sort your posts on numeric (custom field) values.", 'cpac' ) . "</li>
						<li><strong>". __( "Date", 'cpac' ) . "</strong><br/>". sprintf( __( "Value: Can be unix time stamp or a date format as described in the <a href='%s'>Codex</a>. You can change the outputted date format at the <a href='%s'>general settings</a> page.", 'cpac' ), 'http://codex.wordpress.org/Formatting_Date_and_Time', get_admin_url() . 'options-general.php' ) . "</li>
						<li><strong>". __( "Post Titles", 'cpac' ) . "</strong><br/>". __( "Value: can be one or more Post ID's (seperated by ',').", 'cpac' ) . "</li>
						<li><strong>". __( "Usernames", 'cpac' ) . "</strong><br/>". __( "Value: can be one or more User ID's (seperated by ',').", 'cpac' ) . "</li>
						<li><strong>". __( "Checkmark", 'cpac' ) . "</strong><br/>". __( "Value: should be a 1 (one) or 0 (zero).", 'cpac' ) . "</li>
						<li><strong>". __( "Color", 'cpac' ) . "</strong><br/>". __( "Value: hex value color, such as #808080.", 'cpac' ) . "</li>
						<li><strong>". __( "Counter", 'cpac' ) . "</strong><br/>". __( "Value: Can be either a string or array. This will display a count of the number of times the meta key is used by the item.", 'cpac' ) . "</li>
					</ul>
				"
			)
		);

		foreach ( $tabs as $k => $tab ) {
			$screen->add_help_tab( array(
				'id'		=> 'cpac-tab-'.$k,
				'title'		=> $tab['title'],
				'content'	=> $tab['content'],
			));
		}
	}

	/**
	 * @since 1.0
	 * @param string $storage_model URL type.
	 * @return string Url.
	 */
	function get_url( $type ) {

		$site_url = 'http://www.admincolumns.com';

		$urls = array(
			'main'				=> $site_url,
			'pricing'			=> $site_url . '/pricing-purchase/',
			'codepress'			=> 'http://www.codepresshq.com',
			'admincolumns'		=> $site_url,
			'admincolumnspro'	=> $site_url,
			'documentation'		=> $site_url . '/documentation/',
			'feedback'			=> 'http://www.codepresshq.com/contact',
			'plugins'			=> 'http://wordpress.org/extend/plugins/codepress-admin-columns/',
			'support'			=> 'http://wordpress.org/tags/codepress-admin-columns/',
		);

		if ( ! isset( $urls[ $type ] ) )
			return false;

		return $urls[ $type ];
	}

	/**
	 * @since 2.0
	 */
	public function uses_custom_fields() {

		$old_columns = get_option('cpac_options');

		if ( empty( $old_columns['columns'] ) ) return false;

		foreach ( $old_columns['columns'] as $columns ) {
			foreach ( $columns as $id => $values ) {
				if ( strpos( $id, 'column-meta-' ) !== false )
					return true;
			}
		}

		return false;
	}

	/**
	 * Welcome screen
	 *
	 * @since 2.0
	 */
	public function welcome_screen() {

		// Should only be set after upgrade
		$show_welcome = false !== get_transient('cpac_show_welcome');

		// Should only be set manual
		if ( isset( $_GET['info'] ) ) {
			$show_welcome = true;
		}

		if ( ! $show_welcome ) {
			return false;
		}

		// Set check that welcome should not be displayed.
		delete_transient('cpac_show_welcome');

		$tab = !empty( $_GET['info'] ) ? $_GET['info'] : 'whats-new';

		?>

		<div id="cpac-welcome" class="wrap about-wrap">

			<h1><?php _e( "Welcome to Admin Columns",'cpac'); ?> <?php echo CPAC_VERSION; ?></h1>

			<div class="about-text">
				<?php _e( "Thank you for updating to the latest version!", 'cpac' ); ?>
				<?php _e( "Admin Columns is more polished and enjoyable than ever before. We hope you like it.", 'cpac' ); ?>
			</div>

			<div class="cpac-content-body">
				<h2 class="nav-tab-wrapper">
					<a class="cpac-tab-toggle nav-tab <?php if( $tab == 'whats-new' ){ echo 'nav-tab-active'; } ?>" href="<?php echo $this->get_settings_url( 'info' ); ?>whats-new"><?php _e( "What’s New", 'cpac' ); ?></a>
					<a class="cpac-tab-toggle nav-tab <?php if( $tab == 'changelog' ){ echo 'nav-tab-active'; } ?>" href="<?php echo $this->get_settings_url( 'info' ); ?>changelog"><?php _e( "Changelog", 'cpac' ); ?></a>
				</h2>

			<?php if ( 'whats-new' === $tab ) : ?>

				<h3><?php _e( "Important", 'cpac' ); ?></h3>

				<h4><?php _e( "Database Changes", 'cpac' ); ?></h4>
				<p><?php _e("The database has been changed between versions 1 and 2. But we made sure you can still roll back to version 1x without any issues.",'cpac'); ?></p>

			<?php if ( get_option( 'cpac_version', false ) < CPAC_UPGRADE_VERSION ) : ?>
				<p><?php _e("Make sure you backup your database and then click",'cpac'); ?> <a href="<?php echo $this->get_settings_url( 'upgrade' ); ?>" class="button-primary"><?php _e( "Upgrade Database", 'cpac' );?></a></p>
			<?php endif; ?>

				<h4><?php _e( "Potential Issues", 'cpac' ); ?></h4>
				<p><?php _e( "Do to the sizable refactoring the code, surounding Addons and action/filters, your website may not operate correctly. It is important that you read the full", 'cpac' ); ?> <a href="<?php echo $this->get_url('admincolumns'); ?>migrating-from-v1-to-v2" target="_blank"><?php _e( "Migrating from v1 to v2", 'cpac' ); ?></a> <?php _e( "guide to view the full list of changes.", 'cpac' ); ?> <?php printf( __( 'When you have found a bug please <a href="%s">report them to us</a> so we can fix it in the next release.', 'cpac'), 'mailto:info@codepress.nl' ); ?></p>

				<div class="cpac-alert cpac-alert-error">
					<p><strong><?php _e( "Important!", 'cpac' ); ?></strong> <?php _e( "If you updated the Admin Columns plugin without prior knowledge of such changes, Please roll back to the latest", 'cpac' ); ?> <a href="http://downloads.wordpress.org/plugin/codepress-admin-columns.1.4.9.zip"> <?php _e( "version 1", 'cpac' ); ?></a> <?php _e( "of this plugin.", 'cpac' ); ?></p>
				</div>

			<?php endif; ?>
			<?php if ( 'changelog' === $tab ) : ?>

				<h3><?php _e("Changelog for", 'cpac'); ?> <?php echo CPAC_VERSION; ?></h3>
				<?php

				$items = file_get_contents( CPAC_DIR . 'readme.txt' );
				$items = explode('= ' . CPAC_VERSION . ' =', $items);
				$items = end( $items );
				$items = current( explode("\n\n", $items) );
				$items = current( explode("= ", $items) );
				$items = array_filter( array_map('trim', explode("*", $items)) );

				?>
				<ul class="cpac-changelog">
				<?php foreach( $items as $item ) :
					$item = explode('http', $item);
				?>
					<li><?php echo $item[0]; ?><?php if( isset($item[1]) ): ?><a href="http<?php echo $item[1]; ?>" target="_blank"><?php _e("Learn more", 'cpac'); ?></a><?php endif; ?></li>
				<?php endforeach; ?>
				</ul>

			<?php endif; ?>
				<hr/>

			</div><!--.cpac-content-body-->

			<div class="cpac-content-footer">
				<a class="button-primary button-large" href="<?php echo $this->get_settings_url( 'general' ); ?>"><?php _e("Start using Admin Columns",'cpac'); ?></a>
			</div><!--.cpac-content-footer-->

		</div>
		<?php

		return true;
	}

	/**
	 * @since 2.2
	 */
	public function display_menu_by_type( $menu_type = '', $label = '', $active_item = '' ) {

		$storage_models_by_type = array();

		foreach( $this->cpac->storage_models as $k => $storage_model ) {
			if ( $menu_type == $storage_model->menu_type ) {
				$storage_models_by_type[ $menu_type ][ $k ] = $storage_model;
			}
		}

		if ( ! empty( $storage_models_by_type[ $menu_type ] ) ) { $count = 0; ?>
			<ul class="subsubsub">
				<li class="first"><?php echo $label; ?>: </li>
			<?php foreach ( $storage_models_by_type[ $menu_type ] as $storage_model ) : ?>
				<li><?php echo $count++ != 0 ? ' | ' : ''; ?><a href="#cpac-box-<?php echo $storage_model->key; ?>" <?php echo $storage_model->is_menu_type_current( $active_item ) ? ' class="current"' : '';?> ><?php echo $storage_model->label; ?></a></li>
			<?php endforeach; ?>
			</ul>
		<?php
		}
	}

	/**
	 * @since 1.0
	 */
	public function display_settings() {
	?>
		<table class="form-table cpac-form-table settings">
			<tbody>

				<tr class="general">
					<th scope="row">
						<h3><?php _e( 'General Settings', 'cpac' ); ?></h3>
						<p><?php _e( 'Customize your Admin Columns settings.', 'cpac' ); ?></p>
					</th>
					<td class="padding-22">
						<div class="cpac_general">
							<form method="post" action="options.php">
								<?php settings_fields( 'cpac-general-settings' ); ?>
								<?php $options = get_option( 'cpac_general_options' ); ?>
								<p>
									<label for="show_edit_button">
										<input name="cpac_general_options[show_edit_button]" type="hidden" value="0" >
										<input name="cpac_general_options[show_edit_button]" id="show_edit_button" type="checkbox" value="1" <?php checked( ! isset( $options['show_edit_button'] ) || ( '1' == $options['show_edit_button'] ) ); ?>>
										<?php _e( 'Show "Edit Columns" button on admin screens. Default is <code>on</code>.', 'cpac' ); ?>
									</label>
								</p>

								<?php do_action( 'cac/settings/general', $options ); ?>

								<p>
									<input type="submit" class="button" value="<?php _e( 'Save' ); ?>" />
								</p>
							</form>
						</div>
					</td>
				</tr><!--.general-->

				<?php

				/** Allow plugins to add their own custom settings to the settings page. */
				if ( $groups = apply_filters( 'cac/settings/groups', array() ) ) {

					foreach ( $groups as $id => $group ) {

						$title 			= isset( $group['title'] ) ? $group['title'] : '';
						$description 	= isset( $group['description'] ) ? $group['description'] : '';

						?>
				<tr>
					<th scope="row">
						<h3><?php echo $title; ?></h3>
						<p><?php echo $description; ?></p>
					</th>
					<td class="padding-22">
						<?php

						/** Use this Hook to add additonal fields to the group */
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
						<h3><?php _e( 'Restore Settings', 'cpac' ); ?></h3>
						<p><?php _e( 'This will delete all column settings and restore the default settings.', 'cpac' ); ?></p>
					</th>
					<td class="padding-22">
						<form method="post" action="">
							<?php wp_nonce_field( 'restore-all','_cpac_nonce'); ?>
							<input type="hidden" name="cpac_action" value="restore_all" />
							<input type="submit" class="button" name="cpac-restore-defaults" value="<?php _e( 'Restore default settings', 'cpac' ) ?>" onclick="return confirm('<?php _e("Warning! ALL saved admin columns data will be deleted. This cannot be undone. \'OK\' to delete, \'Cancel\' to stop", 'cpac' ); ?>');" />
						</form>
					</td>
				</tr><!--.restore-->

			</tbody>
		</table>

	<?php
	}

	/**
	 * @since 1.0
	 */
	public function display() {

		if ( $this->welcome_screen() ) {
			return;
		}

		$tabs = array(
			'general'	=> __( 'Admin Columns', 'cpac' ),
			'settings'	=> __( 'Settings', 'cpac' ),
			'addons'	=> __( 'Add-ons', 'cpac' )
		);

		/**
		 * Filter the tabs on the settings screen
		 *
		 * @param array $tabs Available tabs
		 */
		$tabs = apply_filters( 'cac/settings/tabs', $tabs );

		$current_tab = ( empty( $_GET['tab'] ) ) ? 'general' : sanitize_text_field( urldecode( $_GET['tab'] ) );

		$post_types = array_values( $this->cpac->get_post_types() );
		$first 		= array_shift( $post_types );
		?>
		<div id="cpac" class="wrap">
			<?php screen_icon( 'codepress-admin-columns' ); ?>
			<h2 class="nav-tab-wrapper cpac-nav-tab-wrapper">
				<?php foreach( $tabs as $name => $label ) : ?>
					<a href="<?php echo $this->get_settings_url( 'admin' ) . "&amp;tab={$name}"; ?>" class="nav-tab<?php if( $current_tab == $name ) echo ' nav-tab-active'; ?>"><?php echo $label; ?></a>
				<?php endforeach; ?>
			</h2>

			<?php do_action( 'cpac_messages' ); ?>

			<?php
			switch ( $current_tab ) :
				case 'general':
					?>
					<div class="cpac-menu">
						<?php $this->display_menu_by_type( 'post', __( 'Posttypes', 'cpac' ), $first ); ?>
						<?php $this->display_menu_by_type( 'other', __( 'Others', 'cpac' ) ); ?>
						<?php $this->display_menu_by_type( 'taxonomy', __( 'Taxonomies', 'cpac' ) ); ?>
					</div>

					<?php do_action( 'cac/settings/after_menu' ); ?>

					<?php $count = 0; ?>
					<?php foreach ( $this->cpac->storage_models as $storage_model ) : ?>
						<div class="columns-container" data-type="<?php echo $storage_model->key ?>"<?php echo $storage_model->is_menu_type_current( $first ) ? '' : ' style="display:none"'; ?>>

							<div class="columns-left">
								<div id="titlediv">
									<h2>
										<?php echo $storage_model->label; ?>
										<?php $storage_model->screen_link(); ?>
									</h2>
								</div>

								<?php if ( $storage_model->is_using_php_export() ) : ?>
									<div class="error below-h2">
										<p><?php printf( __( 'The columns for %s are set up via PHP and can therefore not be edited in the admin panel.', 'cpac' ), '<strong>' . $storage_model->label . '</strong>' ); ?></p>
									</div>
								<?php endif; ?>
							</div>

							<div class="columns-right">
								<div class="columns-right-inside">
									<?php if ( ! $storage_model->is_using_php_export() ) : ?>
										<div class="sidebox" id="form-actions">
											<h3>
												<?php _e( 'Store settings', 'cpac' ) ?>
											</h3>
											<?php $has_been_stored = $storage_model->get_stored_columns() ? true : false; ?>
											<div class="form-update">
												<a href="javascript:;" class="button-primary submit-update"><?php echo $has_been_stored ? __( 'Update' ) : __('Save'); ?> <?php echo $storage_model->label; ?></a>
											</div>
											<?php if ( $has_been_stored ) : ?>
											<div class="form-reset">
												<a href="<?php echo add_query_arg( array( '_cpac_nonce' => wp_create_nonce('restore-type'), 'cpac_key' => $storage_model->key, 'cpac_action' => 'restore_by_type' ), $this->get_settings_url( 'admin' ) ); ?>" class="reset-column-type" onclick="return confirm('<?php printf( __( "Warning! The %s columns data will be deleted. This cannot be undone. \'OK\' to delete, \'Cancel\' to stop", 'cpac' ), $storage_model->label ); ?>');">
													<?php _e( 'Restore', 'cpac' ); ?> <?php echo $storage_model->label; ?> <?php _e( 'columns', 'cpac' ); ?>
												</a>
											</div>
											<?php endif; ?>

											<?php do_action( 'cac/settings/form_actions', $storage_model ); ?>

										</div><!--form-actions-->
									<?php endif; ?>

									<?php if ( ! class_exists( 'CAC_Addon_Pro' ) ) : ?>
									<?php $url_args = array(
										'utm_source' => 'plugin-installation',
										'utm_medium' => 'banner',
										'utm_campaign' => 'plugin-installation'
									); ?>
									<div class="sidebox" id="pro-version">
										<div class="padding-box cta">
											<h3>
												<a href="<?php echo add_query_arg( array_merge( $url_args, array( 'utm_content' => 'title' ) ), $this->get_url( 'admincolumnspro' ) ); ?>"><?php _e( 'Get Admin Columns Pro', 'cpac' ) ?></a>
											</h3>
											<div class="inside">
												<ul>
													<li><a href="<?php echo add_query_arg( array_merge( $url_args, array( 'utm_content' => 'usp-sorting' ) ), $this->get_url( 'admincolumnspro' ) ) ?>"><?php _e( 'Add Sorting', 'cpac' ); ?></a></li>
													<li><a href="<?php echo add_query_arg( array_merge( $url_args, array( 'utm_content' => 'usp-filtering' ) ), $this->get_url( 'admincolumnspro' ) ) ?>"><?php _e( 'Add Filtering', 'cpac' ); ?></a></li>
													<li><a href="<?php echo add_query_arg( array_merge( $url_args, array( 'utm_content' => 'usp-import-export' ) ), $this->get_url( 'admincolumnspro' ) ) ?>"><?php _e( 'Add Import/Export', 'cpac' ); ?></a></li>
													<li><a href="<?php echo add_query_arg( array_merge( $url_args, array( 'utm_content' => 'usp-editing' ) ), $this->get_url( 'admincolumnspro' ) ) ?>"><?php _e( 'Add Direct Editing', 'cpac' ); ?></a></li>
												</ul>
												<p>
													<?php printf( __( 'Check out <a href="%s">Admin Columns Pro</a> for more details!', 'cpac' ), add_query_arg( array_merge( $url_args, array( 'utm_content' => 'cta' ) ), $this->get_url( 'admincolumnspro' ) ) ); ?>
												</p>
											</div>
										</div>
									</div>

										<?php
										// @todo: add newsletter
										/* ?>
										<div class="padding-box newsletter">
											<form action="http://codepress.us4.list-manage.com/subscribe/post?u=902ae7f162ce5bc38a0bc8a4f&amp;id=183e843a76" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" target="_blank">
												<?php $user = wp_get_current_user(); ?>
												<p>
													<?php _e ( "Subscribe to receive news &amp; updates below.", 'cpac' ); ?>
												</p>
												<div class="mc-field-group">
													<label for="mce-FNAME"><?php _e( 'First Name', 'cpac' ); ?></label>
													<input type="text" value="<?php echo trim( esc_attr( $user->first_name ) ); ?>" name="FNAME" class="" id="mce-FNAME">
												</div>
												<div class="mc-field-group">
													<label for="mce-EMAIL"><?php _e( 'Your Email', 'cpac' ); ?></label>
													<input type="email" value="<?php echo trim( esc_attr( $user->user_email ) ); ?>" name="EMAIL" class="required email" id="mce-EMAIL">
												</div>
												<input type="submit" value="Subscribe" name="subscribe" id="mc-embedded-subscribe" class="button">
											</form>
										</div>
										<?php */ ?>


									<div class="sidebox" id="direct-feedback">
										<div id="feedback-choice">
											<h3><?php _e( 'Are you happy with Admin Columns?', 'cpac' ); ?></h3>
											<div class="inside">
												<a href="#" class="yes">Yes</a>
												<a href="#" class="no">No</a>
											</div>
										</div>
										<div id="feedback-support">
											<div class="inside">
												<p><?php _e( "What's wrong? Need help? Let us know!", 'cpac' ); ?></p>
												<p><?php _e( 'Check out our extensive documentation, or you can open a support topic on WordPress.org!', 'cpac' ); ?></p>
												<ul class="share">
													<li>
														<a href="<?php echo add_query_arg( array(
															'utm_source' => 'plugin-installation',
															'utm_medium' => 'feedback-docs-button',
															'utm_campaign' => 'plugin-installation'
														), $this->get_url( 'documentation' ) ); ?>" target="_blank">
															<div class="dashicons dashicons-editor-help"></div> <?php _e( 'Docs', 'cpac' ); ?>
														</a>
													</li>
													<li>
														<a href="https://wordpress.org/support/plugin/codepress-admin-columns" target="_blank">
															<div class="dashicons dashicons-wordpress"></div> <?php _e( 'Forums', 'cpac' ); ?>
														</a>
													</li>
												</ul>
												<div class="clear"></div>
											</div>
										</div>
										<div id="feedback-rate">
											<div class="inside">
												<p><?php _e( "Woohoo! We're glad to hear that!", 'cpac' ); ?></p>
												<p><?php _e( 'We would really love it if you could show your appreciation by giving us a rating on WordPress.org or tweet about Admin Columns!', 'cpac' ); ?></p>
												<ul class="share">
													<li>
														<a href="http://wordpress.org/support/view/plugin-reviews/codepress-admin-columns#postform" target="_blank">
															<div class="dashicons dashicons-star-empty"></div> <?php _e( 'Rate', 'cpac' ); ?>
														</a>
													</li>

													<li>
														<a href="<?php echo add_query_arg( array(
															'hashtags' => 'wordpress',
															'text' => urlencode( "I'm using Admin Columns for WordPress!" ),
															'url' => urlencode( 'http://wordpress.org/plugins/codepress-admin-columns/' ),
															'via' => 'wpcolumns'
														), 'https://twitter.com/intent/tweet' ); ?>" target="_blank">
															<div class="dashicons dashicons-twitter"></div> <?php _e( 'Tweet', 'cpac' ); ?>
														</a>
													</li>

													<li>
														<a href="<?php echo add_query_arg( array(
															'utm_source' => 'plugin-installation',
															'utm_medium' => 'feedback-purchase-button',
															'utm_campaign' => 'plugin-installation'
														), $this->get_url( 'admincolumnspro' ) ); ?>" target="_blank">
															<div class="dashicons dashicons-cart"></div> <?php _e( 'Buy Pro', 'cpac' ); ?>
														</a>
													</li>
												</ul>
												<div class="clear"></div>
											</div>
										</div>
									</div>

									<?php endif; ?>

									<div class="sidebox" id="plugin-support">
										<h3><?php _e( 'Support', 'cpac' ); ?></h3>
										<div class="inside">
											<?php if ( version_compare( get_bloginfo( 'version' ), '3.2', '>' ) ) : ?>
												<p><?php _e( 'Check the <strong>Help</strong> section in the top-right screen.', 'cpac' ); ?></p>
											<?php endif; ?>
											<p>
												<?php printf( __("For full documentation, bug reports, feature suggestions and other tips <a href='%s'>visit the Admin Columns website</a>", 'cpac' ), $this->get_url('documentation') ); ?>
											</p>
										</div>
									</div><!--plugin-support-->

								</div><!--.columns-right-inside-->
							</div><!--.columns-right-->

							<div class="columns-left">
								<div class="cpac-boxes">
									<?php if ( ! $storage_model->is_using_php_export() ) : ?>
										<div class="cpac-columns">

											<form method="post" action="">
												<?php wp_nonce_field( 'update-type', '_cpac_nonce'); ?>

												<input type="hidden" name="cpac_key" value="<?php echo $storage_model->key; ?>" />
												<input type="hidden" name="cpac_action" value="update_by_type" />

												<?php
												foreach ( $storage_model->columns as $column ) {
													$column->display();
												}
												?>
											</form>

										</div><!--.cpac-columns-->

										<div class="column-footer">
											<div class="order-message"><?php _e( 'Drag and drop to reorder', 'cpac' ); ?></div>

											<div class="button-container">
												<a href="javascript:;" class="add_column button button-primary">+ <?php _e( 'Add Column', 'cpac' );?></a><br/>
											</div>

										</div><!--.cpac-column-footer-->
									<?php endif; ?>
								</div><!--.cpac-boxes-->
							</div><!--.columns-left-->
							<div class="clear"></div>

							<div class="for-cloning-only" style="display:none">
								<?php
								foreach ( $storage_model->get_registered_columns() as $column ) {
									$column->display();
								}
								?>
							</div>
						</div><!--.columns-container-->
					<?php endforeach; // storage_models ?>

					<div class="clear"></div>
					<?php
					break; // case: general
				case 'settings' :
					$this->display_settings();
					break;
				case 'addons' :
					$this->tab_addons();
					break;
				case 'help' :
					//$this->tab_addons();
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

		$addon_groups = $this->cpac->addons()->get_addon_groups();
		$grouped_addons = $this->cpac->addons()->get_available_addons( true );
		?>
		<?php foreach ( $grouped_addons as $group_name => $addons ) : ?>
			<h3><?php echo $addon_groups[ $group_name ]; ?></h3>

			<ul class="cpac-addons">
				<?php foreach ( $addons as $addon_name => $addon ) : ?>
					<li>
						<div class="cpac-addon-content">
							<?php if ( ! empty( $addon['image'] ) ) : ?>
								<img src="<?php echo $addon['image']; ?>" />
							<?php else : ?>
								<h3><?php echo $addon['title']; ?></h3>
							<?php endif; ?>
						</div>
						<div class="cpac-addon-header">
							<h3><?php echo $addon['title']; ?></h3>
							<p><?php echo $addon['description']; ?></p>
						</div>
						<div class="cpac-addon-actions">
							<?php

							// Installed..
							if ( ( $plugin_basename = $this->cpac->addons()->get_installed_addon_plugin_basename( $addon_name ) ) ) : ?>
								<?php if ( is_plugin_active( $plugin_basename ) ) : ?>
									<?php $deactivation_url = wp_nonce_url( add_query_arg( array(
										'action' => 'deactivate',
										'plugin' => urlencode( $plugin_basename ),
										'cpac-redirect' => true
									), admin_url( 'plugins.php' ) ), 'deactivate-plugin_' . $plugin_basename ); ?>
									<a href="#" class="button button-disabled cpac-installed"><?php _e( 'Active', 'cpac' ); ?></a>
									<a href="<?php echo esc_attr( $deactivation_url ); ?>" class="button right"><?php _e( 'Deactivate', 'cpac' ); ?></a>
								<?php else : ?>
									<?php $activation_url = wp_nonce_url( add_query_arg( array(
										'action' => 'activate',
										'plugin' => urlencode( $plugin_basename ),
										'cpac-redirect' => true
									), admin_url( 'plugins.php' ) ), 'activate-plugin_' . $plugin_basename ); ?>
									<a href="#" class="button button-disabled cpac-installed"><?php _e( 'Installed', 'cpac' ); ?></a>
									<a href="<?php echo esc_attr( $activation_url ); ?>" class="button right"><?php _e( 'Activate', 'cpac' ); ?></a>
								<?php endif; ?>
							<?php

							// Not installed...
							else :

								// Got ACP?
								if ( class_exists('CAC_Addon_Pro') ) :
									$install_url = wp_nonce_url( add_query_arg( array(
										'action' => 'install',
										'plugin' => $addon_name,
									), $this->get_settings_url( 'addons' ) ), 'install-cac-addon' );
									?>
									<a href="<?php echo esc_attr( $install_url ); ?>" class="button"><?php _e( 'Download & Install', 'cpac' ); ?></a>
								<?php

								// Get ACP?
								else : ?>
									<a target="_blank" href="<?php echo esc_attr( $this->get_url('pricing') ); ?>" class="button"><?php _e( 'Get this add-on', 'cpac' ); ?></a>
								<?php endif; ?>
							<?php endif; ?>
						</div>
					</li>
				<?php endforeach; // addons ?>
			</ul>
		<?php endforeach; // grouped_addons ?>
		<?php
	}
}