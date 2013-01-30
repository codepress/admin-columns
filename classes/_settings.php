<?php

/**
 * CPAC_Settings Class
 *
 * @since 2.0.0
 */
class CPAC_Settings {

	/**
	 * Column Settings slug
	 *
	 * @since 2.0.0
	 *
	 * @var string Page slug.
	 */
	private $column_settings_slug;

	/**
	 * Constructor
	 *
	 * @since 2.0.0
	 */
	function __construct() {
		// register settings
		add_action( 'admin_menu', array( $this, 'settings_menu' ) );

		// action ajax
		add_action( 'wp_ajax_cpac_addon_activation', array( $this, 'ajax_activation' ) );

		// handle requests gets a low priority so it will trigger when all other plugins have loaded their columns
		add_action( 'admin_init', array( $this, 'handle_requests' ), 1000 );
	}

	/**
	 * Admin Menu.
	 *
	 * Create the admin menu link for the settings page.
	 *
	 * @since 1.0.0
	 */
	public function settings_menu() {
		// Utility Page
		$page = add_menu_page(
			__( 'Admin Columns Settings', CPAC_TEXTDOMAIN ),
			__( 'Admin Columns', CPAC_TEXTDOMAIN ),
			'manage_options',
			CPAC_SLUG,
			array( $this, 'column_settings' ),
			false,
			98 // at the end of the settings menu
		);

		// set admin page
		$this->column_settings_slug = $page;

		// settings page specific styles and scripts
		add_action( "admin_print_styles-{$page}", array( $this, 'admin_styles' ) );
		add_action( "admin_print_scripts-{$page}", array( $this, 'admin_scripts' ) );

		// add help tabs
		add_action( "load-{$page}", array( $this, 'help_tabs' ) );

		// Settings Page
		$general_page = add_submenu_page(
			'codepress-admin-columns',
			__( 'Settings', CPAC_TEXTDOMAIN ),
			__( 'Settings', CPAC_TEXTDOMAIN ),
			'manage_options',
			'cpac-settings',
			array( $this, 'general_settings' )
		);

		add_action( "admin_print_styles-{$general_page}", array( $this, 'admin_styles' ) );
		add_action( "admin_print_scripts-{$general_page}", array( $this, 'admin_scripts' ) );
	}

	/**
	 * Register admin css
	 *
	 * @since 1.0.0
	 */
	public function admin_styles() {
		wp_enqueue_style( 'wp-pointer' );
		wp_enqueue_style( 'jquery-ui-lightness', CPAC_URL.'/assets/ui-theme/jquery-ui-1.8.18.custom.css', array(), CPAC_VERSION, 'all' );
		wp_enqueue_style( 'cpac-admin', CPAC_URL.'/assets/css/admin-column.css', array(), CPAC_VERSION, 'all' );
		wp_enqueue_style( 'cpac-multi-select', CPAC_URL.'/assets/css/multi-select.css', array(), CPAC_VERSION, 'all' );
	}

	/**
	 * Register admin scripts
	 *
	 * @since 1.0.0
	 */
	public function admin_scripts() {
		wp_enqueue_script( 'wp-pointer' );

		// columns
		wp_enqueue_script( 'jquery-ui-slider' );
		wp_enqueue_script( 'cpac-admin-columns', CPAC_URL.'/assets/js/admin-columns.js', array( 'jquery', 'dashboard', 'jquery-ui-sortable' ), CPAC_VERSION );
		wp_enqueue_script( 'cpac-custom-fields', CPAC_URL.'/assets/js/custom-fields.js', array( 'cpac-admin-columns' ), CPAC_VERSION );
		wp_enqueue_script( 'cpac-jquery-multi-select', CPAC_URL.'/assets/js/jquery.multi-select.js', array( 'jquery' ), CPAC_VERSION );

		// javascript translations
		wp_localize_script( 'cpac-admin-columns', 'cpac_i18n', array(
			'fill_in' 		=> __( 'Enter your activation code', CPAC_TEXTDOMAIN ),
			'unrecognised'	=> __( 'Activation code unrecognised', CPAC_TEXTDOMAIN ),
			'remove'		=> __( 'Remove', CPAC_TEXTDOMAIN ),
			'customfield'	=> __( 'Custom Field', CPAC_TEXTDOMAIN ),
		));
	}

	/**
	 * Ajax activation
	 *
	 * @since 1.3.1
	 *
	 * @return string Masked key ( JSON encode ).
	 */
	public function ajax_activation() {
		// keys
		$key 	= $_POST['key'];
		$type 	= $_POST['type'];

		$licence = new CPAC_Licence( $type );

		// update key
		if ( $key == 'remove' ) {
			$licence->remove_license_key();
		}

		// set license key
		elseif ( $licence->check_remote_key( $key ) ) {

			// set key
			$licence->set_license_key( $key );

			// returned masked key
			echo json_encode( $licence->get_masked_license_key() );
		}

		exit;
	}

	/**
	 * Handle requests.
	 *
	 * @since 1.0.0
	 */
	public function handle_requests() {
		// only handle updates from the admin columns page
		if ( ! ( isset($_REQUEST['page']) && in_array( $_REQUEST['page'], array( CPAC_SLUG, CPAC_SETTINGS_SLUG ) ) && isset( $_REQUEST['cpac_action'] ) ) )
			return false;

		$action = isset( $_REQUEST['cpac_action'] ) ? $_REQUEST['cpac_action'] 	: '';
		$nonce  = isset( $_REQUEST['_cpac_nonce'] ) ? $_REQUEST['_cpac_nonce'] 	: '';
		$type 	= isset( $_REQUEST['cpac_type'] ) 	? $_REQUEST['cpac_type'] 	: '';

		switch ( $action ) :

			case 'update_by_type' :
				if ( wp_verify_nonce( $nonce, 'update-type' ) ) {
					$this->store_wp_default_columns();
					$this->update_settings_by_type( $type );
				}
				break;

			case 'restore_by_type' :
				if ( wp_verify_nonce( $nonce, 'restore-type' ) ) {
					$this->restore_settings_by_type( $type );
				}
				break;

			case 'restore_all' :
				if ( wp_verify_nonce( $nonce, 'restore-all' ) ) {
					$this->restore_settings();
				}
				break;

		endswitch;
	}

	/**
	 * Get export multiselect options
	 *
	 * Gets multi select options to use in a HTML select element
	 *
	 * @since 1.5.0
	 * @return array Multiselect options
	 */
	public function get_export_multiselect_options() {
		$options = array();

		foreach ( CPAC_Utility::get_types() as $type ) {

			if ( ! CPAC_Utility::get_stored_columns( $type->storage_key ) )
				continue;

			// General group
			if ( in_array( $type->storage_key, array( 'wp-comments', 'wp-links', 'wp-users', 'wp-media' ) ) ) {
				$options['general'][] = $type;
			}

			// Post(types) group
			else {
				$options['posts'][] = $type;
			}
		}

		return $options;
	}

	/**
	 * Stores WP default columns
	 *
	 * This will store columns that are set by WordPress core or theme
	 *
	 * @since 1.2.0
	 */
	private function store_wp_default_columns() {
		// stores the default columns that are set by WP or theme.
		$wp_default_columns = array();

		// Posts
		foreach ( CPAC_Utility::get_post_types() as $post_type ) {
			$type = new CPAC_Columns_Post( $post_type );
			$wp_default_columns[$type->storage_key] = $type->get_default_columns();
		}

		// Users
		$type = new CPAC_Columns_User();
		$wp_default_columns[$type->storage_key] = $type->get_default_columns();

		// Media
		$type = new CPAC_Columns_Media();
		$wp_default_columns[$type->storage_key] = $type->get_default_columns();

		// Links
		$type = new CPAC_Columns_Link();
		$wp_default_columns[$type->storage_key] = $type->get_default_columns();

		// Comments
		$type = new CPAC_Columns_Comment();
		$wp_default_columns[$type->storage_key] = $type->get_default_columns();

		update_option( 'cpac_options_default', $wp_default_columns );
	}

	/**
	 * Update Settings by Type
	 *
	 * @since     1.5
	 */
	private function update_settings_by_type( $type ) {
		if ( ! $type )
			return false;

		$options = (array) get_option( 'cpac_options' );

		if ( ! empty( $_POST['cpac_options'] ) ) {
			$options['columns'][$type] = stripslashes_deep( $_POST['cpac_options']['columns'][$type] );

			// place active columns on top
			$options['columns'][$type] = $this->reorder_by_state( $options['columns'][$type] );

			// save settings
			update_option( 'cpac_options', $options );

			// set admin notice
			CPAC_Utility::admin_message( "<p>" . __( 'Settings updated.',  CPAC_TEXTDOMAIN ) . "</p>", 'updated');
		}
	}

	/**
	 * Restore Defaults by Type
	 *
	 * @since 1.5.0
	 */
	private function restore_settings_by_type( $type ) {
		if ( ! $type )
			return false;

		// restore stored options
		$options = get_option( 'cpac_options' );

		if ( isset(  $options['columns'][$type] ) ) {
			unset( $options['columns'][$type] );
		}
		update_option( 'cpac_options', $options );

		// restore default options
		$options = get_option( 'cpac_options_default' );

		if ( isset(  $options['columns'][$type] ) ) {
			unset( $options['columns'][$type] );
		}
		update_option( 'cpac_options_default', $options );

		CPAC_Utility::admin_message( "<p>" . __( 'Settings succesfully restored.',  CPAC_TEXTDOMAIN ) . "</p>", 'updated' );
	}

	/**
	 * Restore defaults
	 *
	 * @since 1.0.0
	 */
	private function restore_settings() {
		delete_option( 'cpac_options' );
		delete_option( 'cpac_options_default' );

		CPAC_Utility::admin_message( "<p>" . __( 'Default settings succesfully restored.',  CPAC_TEXTDOMAIN ) . "</p>", 'updated');
	}

	/**
	 * Reorder columns by state ( inactive/active )
	 *
	 * Active columns are set on top of the list.
	 *
	 * @since 1.5.0
	 *
	 * @param string $options Columns Options.
	 * @return array Reordered Columns Options.
	 */
	public function reorder_by_state( $options ) {
		if ( empty($options) )
			return array();

		$active = $inactive = array();

		foreach ( $options as $label => $box ) {
			if ( 'on' == $box['state'] ) {
				$active[$label] = $box;
			}
			else {
				$inactive[$label] = $box;
			}
		}

		return array_merge( $active, $inactive);
	}

	/**
	 * Add help tabs
	 *
	 * @since 1.3.0
	 */
	public function help_tabs() {
		$screen = get_current_screen();

		if ( $screen->id != $this->column_settings_slug || ! method_exists( $screen,'add_help_tab' ) )
			return;

		// add help content
		$tabs = array(
			array(
				'title'		=> __( "Overview", CPAC_TEXTDOMAIN ),
				'content'	=>
					"<h5>Codepress Admin Columns</h5>
					<p>". __( "This plugin is for adding and removing additional columns to the administration screens for post(types), pages, media library, comments, links and users. Change the column's label and reorder them.", CPAC_TEXTDOMAIN ) . "</p>"
			),
			array(
				'title'		=> __( "Basics", CPAC_TEXTDOMAIN ),
				'content'	=>
					"<h5>". __( "Show / Hide", CPAC_TEXTDOMAIN ) . "</h5>
					<p>". __( "You can switch columns on or off by clicking on the checkbox. This will show or hide each column heading.", CPAC_TEXTDOMAIN ) . "</p>
					<h5>". __( "Change order", CPAC_TEXTDOMAIN ) . "</h5>
					<p>". __( "By dragging the columns you can change the order which they will appear in.", CPAC_TEXTDOMAIN ) . "</p>
					<h5>". __( "Change label", CPAC_TEXTDOMAIN ) . "</h5>
					<p>". __( "By clicking on the triangle you will see the column options. Here you can change each label of the columns heading.", CPAC_TEXTDOMAIN ) . "</p>
					<h5>". __( "Change column width", CPAC_TEXTDOMAIN ) . "</h5>
					<p>". __( "By clicking on the triangle you will see the column options. By using the draggable slider yo can set the width of the columns in percentages.", CPAC_TEXTDOMAIN ) . "</p>"
			),
			array(
				'title'		=> __( "Custom Field", CPAC_TEXTDOMAIN ),
				'content'	=>
					"<h5>". __( "'Custom Field' column", CPAC_TEXTDOMAIN ) . "</h5>
					<p>". __( "The custom field colum uses the custom fields from posts and users. There are 10 types which you can set.", CPAC_TEXTDOMAIN ) . "</p>
					<ul>
						<li><strong>". __( "Default", CPAC_TEXTDOMAIN ) . "</strong><br/>". __( "Value: Can be either a string or array. Arrays will be flattened and values are seperated by a ',' comma.", CPAC_TEXTDOMAIN ) . "</li>
						<li><strong>". __( "Image", CPAC_TEXTDOMAIN ) . "</strong><br/>". __( "Value: should contain an image URL or Attachment IDs ( seperated by a ',' comma ).", CPAC_TEXTDOMAIN ) . "</li>
						<li><strong>". __( "Excerpt", CPAC_TEXTDOMAIN ) . "</strong><br/>". __( "Value: This will show the first 20 words of the Post content.", CPAC_TEXTDOMAIN ) . "</li>
						<li><strong>". __( "Multiple Values", CPAC_TEXTDOMAIN ) . "</strong><br/>". __( "Value: should be an array. This will flatten any ( multi dimensional ) array.", CPAC_TEXTDOMAIN ) . "</li>
						<li><strong>". __( "Numeric", CPAC_TEXTDOMAIN ) . "</strong><br/>". __( "Value: Integers only.<br/>If you have the 'sorting addon' this will be used for sorting, so you can sort your posts on numeric (custom field) values.", CPAC_TEXTDOMAIN ) . "</li>
						<li><strong>". __( "Date", CPAC_TEXTDOMAIN ) . "</strong><br/>". sprintf( __( "Value: Can be unix time stamp or a date format as described in the <a href='%s'>Codex</a>. You can change the outputted date format at the <a href='%s'>general settings</a> page.", CPAC_TEXTDOMAIN ), 'http://codex.wordpress.org/Formatting_Date_and_Time', get_admin_url() . 'options-general.php' ) . "</li>
						<li><strong>". __( "Post Titles", CPAC_TEXTDOMAIN ) . "</strong><br/>". __( "Value: can be one or more Post ID's (seperated by ',').", CPAC_TEXTDOMAIN ) . "</li>
						<li><strong>". __( "Usernames", CPAC_TEXTDOMAIN ) . "</strong><br/>". __( "Value: can be one or more User ID's (seperated by ',').", CPAC_TEXTDOMAIN ) . "</li>
						<li><strong>". __( "Checkmark", CPAC_TEXTDOMAIN ) . "</strong><br/>". __( "Value: should be a 1 (one) or 0 (zero).", CPAC_TEXTDOMAIN ) . "</li>
						<li><strong>". __( "Color", CPAC_TEXTDOMAIN ) . "</strong><br/>". __( "Value: hex value color, such as #808080.", CPAC_TEXTDOMAIN ) . "</li>
					</ul>
				"
			)
		);

		foreach ( $tabs as $k => $tab ) {
			$screen->add_help_tab( array(
				'id'		=> 'cpac-tab-'.$k, 	// unique id
				'title'		=> $tab['title'],	// label
				'content'	=> $tab['content'], // body
			));
		}
	}

	/**
	 * Checks if menu type is currently viewed
	 *
	 * @since 1.0.0
	 *
	 * @param string $storage_key
	 * @return bool
	 */
	function is_menu_type_current( $storage_key ) {
		// referer
		$referer = ! empty($_REQUEST['cpac_type']) ? $_REQUEST['cpac_type'] : '';

		// get first element from post-types
		$first = array_shift( array_values( CPAC_Utility::get_post_types() ) );

		// display the page that was being viewed before saving
		if ( $referer ) {
			if ( $referer == CPAC_Utility::sanitize_string( $storage_key ) ) {
				return true;
			}

		// settings page has not yet been saved
		} elseif ( $first == $storage_key  ) {
			return true;
		}

		return false;
	}

	/**
	 * Get all image sizes
	 *
	 * @since 1.0.0
	 *
	 * @return array Image Sizes.
	 */
	function get_all_image_sizes() {
		$image_sizes = array(
			'thumbnail'	=>	__( "Thumbnail", CPAC_TEXTDOMAIN ),
			'medium'	=>	__( "Medium", CPAC_TEXTDOMAIN ),
			'large'		=>	__( "Large", CPAC_TEXTDOMAIN ),
			'full'		=>	__( "Full", CPAC_TEXTDOMAIN )
		);

		foreach( get_intermediate_image_sizes() as $size ) {
			if ( ! isset( $image_sizes[$size] ) ) {
				$image_sizes[$size] = ucwords( str_replace( '-', ' ', $size) );
			}
		}

		return $image_sizes;
	}

	/**
	 * External Urls
	 *
	 * @since 1.0.0
	 *
	 * @param string $type URL type.
	 * @return string Url.
	 */
	function get_url( $type = '' ) {
		$urls = array(
			'codepress'		=> 'http://www.codepress.nl/',
			'plugins'		=> 'http://wordpress.org/extend/plugins/codepress-admin-columns/',
			'support'		=> 'http://wordpress.org/tags/codepress-admin-columns/',
			'admincolumns'	=> 'http://www.admincolumns.com/',
			'addons'		=> 'http://www.admincolumns.com/addons/',
			'documentation'	=> 'http://www.admincolumns.com/documentation/',
			'feedback'		=> 'http://www.admincolumns.com/feedback/',
		);

		if ( !isset($urls[$type]) )
			return false;

		return $urls[$type];
	}

	/**
	 * Column Settings.
	 *
	 * @since 1.0.0
	 */
	public function column_settings() {
		// Menu
		$menu 	= '';
		$count 	= 1;

		// referer
		$referer = ! empty( $_REQUEST['cpac_type'] ) ? $_REQUEST['cpac_type'] : '';

		// loop
		foreach ( CPAC_Utility::get_types() as $type ) {

			$clean_label = CPAC_Utility::sanitize_string( $type->storage_key );

			// divider
			$divider 	= $count++ == 1 ? '' : ' | ';

			// current
			$current = '';
			if ( $this->is_menu_type_current( $type->storage_key ) ) {
				$current = ' class="current"';
			}

			// menu list
			$menu .= "<li>{$divider}<a{$current} href='#cpac-box-{$clean_label}'>{$type->label}</a></li>\n";
		}

		// Licenses
		$licenses = array(
			'sortable' 		=> new CPAC_Licence( 'sortable' ),
			'customfields' 	=> new CPAC_Licence( 'customfields' )
		);

	?>
		<div id="cpac" class="wrap">

			<?php screen_icon( CPAC_SLUG ); ?>
			<h2><?php _e( 'Admin Columns', CPAC_TEXTDOMAIN ); ?></h2>

			<div class="cpac-menu">
				<ul class="subsubsub">
					<?php echo $menu ?>
				</ul>
			</div>

			<?php foreach ( CPAC_Utility::get_types() as $type ) : ?>

			<div class="columns-container" data-type="<?php echo $type->storage_key ?>"<?php echo $this->is_menu_type_current( $type->storage_key ) ? '' : ' style="display:none"'; ?>>
				<form method="post" action="">

				<?php wp_nonce_field( 'update-type', '_cpac_nonce'); ?>
				<input type="hidden" name="cpac_type" value="<?php echo $type->storage_key; ?>" />

				<div class="columns-left">
					<div id="titlediv">
						<h2><?php echo $type->label; ?></h2>
					</div>
				</div>

				<div class="columns-right">
					<div class="columns-right-inside">
						<div class="sidebox" id="form-actions">
							<h3>
								<?php _e( 'Publish', CPAC_TEXTDOMAIN ) ?>
							</h3>
							<div class="form-reset">
								<a href="<?php echo add_query_arg( array( 'page' => CPAC_SLUG, '_cpac_nonce' => wp_create_nonce('restore-type'), 'cpac_type' => $type->storage_key, 'cpac_action' => 'restore_by_type' ), admin_url("admin.php") ); ?>" class="reset-column-type">
									<?php _e( 'Restore', CPAC_TEXTDOMAIN ); ?> <?php echo $type->label; ?> <?php _e( 'columns', CPAC_TEXTDOMAIN ); ?>
								</a>
							</div>
							<div class="form-update">
								<input type="hidden" name="cpac_action" value="update_by_type" />
								<input type="submit" class="button-primary submit-update" value="<?php _e( 'Update' ) ?>" accesskey="u" >
							</div>
						</div><!--form-actions-->

						<div class="sidebox" id="addon-state">
							<h3><?php _e( 'Addons', CPAC_TEXTDOMAIN ) ?></h3>
							<div class="inside">
								<div class="addon <?php echo $licenses['sortable']->is_unlocked() ? 'enabled' : 'disabled'; ?>">
									<?php _e( 'Sortable Columns', CPAC_TEXTDOMAIN ); ?>
									<a href="<?php echo add_query_arg( array('page' => 'cpac-settings'), admin_url('admin.php') );?>" class="activate"><?php _e( 'activate', CPAC_TEXTDOMAIN ); ?></a>
								</div>
								<div class="addon <?php echo $licenses['customfields']->is_unlocked() ? 'enabled' : 'disabled'; ?>">
									<?php _e( 'Multiple Custom Fields', CPAC_TEXTDOMAIN ); ?>
									<a href="<?php echo add_query_arg( array('page' => 'cpac-settings'), admin_url('admin.php') );?>" class="activate"><?php _e( 'activate', CPAC_TEXTDOMAIN ); ?></a>
								</div>
							</div>
						</div><!--addon-state-->

						<div class="sidebox" id="plugin-support">
							<h3><?php _e( 'Support', CPAC_TEXTDOMAIN ); ?></h3>
							<div class="inside">
								<?php if ( version_compare( get_bloginfo( 'version' ), '3.2', '>' ) ) : ?>
									<p><?php _e( 'Check the <strong>Help</strong> section in the top-right screen.', CPAC_TEXTDOMAIN ); ?></p>
								<?php endif; ?>
								<p><?php printf( __("For full documentation, bug reports, feature suggestions and other tips <a href='%s'>visit the Admin Columns website</a>", CPAC_TEXTDOMAIN ), $this->get_url('documentation') ); ?></p>
							</div>
						</div><!--.form-actions-->

					</div><!--.columns-right-inside-->
				</div><!--.columns-right-->

				<div class="columns-left">
					<div class="cpac-boxes">
						<div class="cpac-columns">

							<?php foreach ( $type->get_column_boxes() as $box ) : ?>

							<div class="cpac-column <?php echo $box->classes; ?>">
								<div class="column-meta">
									<table class="widefat">
										<tbody>
											<tr>
												<td class="column_sort"></td>
												<td class="column_status">
													<input type="hidden" class="cpac-state" name="<?php echo $box->attr_name; ?>[state]" value="<?php echo $box->state; ?>" id="<?php echo $box->attr_for; ?>-state"/>
												</td>
												<td class="column_label">
													<a href="javascript:;">
														<?php echo $box->label; ?>
													</a>
													<span class="meta-label">
													<?php if ( $licenses['sortable']->is_unlocked() && ( $box->sort || in_array( $box->id, array( 'title', 'date' ) ) ) ) : ?>
														<span class="sorting enable"><?php _e( 'sorting',  CPAC_TEXTDOMAIN )?></span>
													<?php endif; ?>
													</span>
												</td>
												<td class="column_type">
													<?php echo $box->type_label; ?>
												</td>
												<td class="column_edit"></td>
											</tr>
										</tbody>
									</table>
								</div><!--.column-meta-->

								<div class="column-form">
									<table class="widefat">
										<tbody>
											<tr class="column_label<?php echo $box->hide_options ? ' hidden' : '' ?>">
												<td class="label">
													<label for="<?php echo $box->attr_for; ?>-label"><?php _e( 'Label', CPAC_TEXTDOMAIN );?></label>
													<p class="description"><?php _e( 'This is the name which will appear as the column header.', CPAC_TEXTDOMAIN ); ?></p>
												</td>
												<td class="input">
													<input type="text" name="<?php echo $box->attr_name; ?>[label]" id="<?php echo $box->attr_for; ?>-label" value="<?php echo $box->label ?>" class="text"/>
												</td>
											</tr>
											<tr class="column_width">
												<td class="label">
													<label for="<?php echo $box->attr_for; ?>-width"><?php _e("Width", CPAC_TEXTDOMAIN ); ?></label>
												</td>
												<td class="input">
													<div class="description width-decription" title="<?php _e( 'default', CPAC_TEXTDOMAIN ); ?>">
														<?php echo $box->width_descr; ?>
													</div>
													<div class="input-width-range"></div>
													<input type="hidden" maxlength="4" class="input-width" name="<?php echo $box->attr_name; ?>[width]" id="<?php echo $box->attr_for; ?>-width" value="<?php echo $box->width; ?>" />
												</td>
											</tr>

											<?php if ( $box->is_field ) : // is custom field ?>
											<tr class="column_field">
												<td class="label">
													<label for="<?php echo $box->attr_for; ?>-field"><?php _e("Custom Field", CPAC_TEXTDOMAIN ) ?></label>
												</td>
												<td class="input">
													<select name="<?php echo $box->attr_name; ?>[field]" id="<?php echo $box->attr_for; ?>-field">
													<?php foreach ( $box->fields as $field ) : ?>
														<option value="<?php echo $field ?>"<?php selected( $field, $box->field ) ?>><?php echo substr($field,0,10) == "cpachidden" ? str_replace('cpachidden','',$field) : $field; ?></option>
													<?php endforeach; ?>
													</select>
												</td>
											</tr>
											<?php endif; ?>

											<?php if ( $box->is_field ) : // is custom field ?>
											<tr class="column_field_type">
												<td class="label">
													<label for="<?php echo $box->attr_for; ?>-field_type"><?php _e("Field Type", CPAC_TEXTDOMAIN ); ?></label>
												</td>
												<td class="input">
													<select name="<?php echo $box->attr_name; ?>[field_type]" id="<?php echo $box->attr_for; ?>-field_type">
													<?php foreach ( $type->get_custom_field_types() as $fieldkey => $fieldtype ) : ?>
														<option value="<?php echo $fieldkey ?>"<?php selected( $fieldkey, $box->field_type ) ?>><?php echo $fieldtype; ?></option>
													<?php endforeach; ?>
													</select>
												</td>
											</tr>
											<?php endif; ?>

											<?php if ( $box->is_image || $box->is_field ) : // is image or custom field ?>
											<tr class="column_image_size<?php echo $box->is_field && ! $box->is_image ? ' hidden' : ''; ?>">
												<td class="label">
													<label for="<?php echo $box->attr_for; ?>-image_size"><?php _e("Preview size", CPAC_TEXTDOMAIN ); ?></label>
												</td>
												<td class="input">
													<?php foreach ( $sizes = $this->get_all_image_sizes() as $id => $image_label ) : ?>

														<?php if ( !in_array( $box->image_size, array_keys($sizes) ) && $box->image_size != 'cpac-custom' ) $box->image_size = key($sizes); ?>

														<label for="<?php echo $box->attr_for; ?>-image-size-<?php echo $id ?>" class="custom-size">
														<input type="radio" value="<?php echo $id; ?>" name="<?php echo $box->attr_name; ?>[image_size]" id="<?php echo $box->attr_for; ?>-image-size-<?php echo $id ?>"<?php checked( $box->image_size, $id ); ?>><?php echo $image_label; ?>
														</label>
													<?php endforeach; ?>
													<div class="custom_image_size">
														<label for="<?php echo $box->attr_for; ?>-image-size-custom" class="custom-size image-size-custom" >
															<input type="radio" value="cpac-custom" name="<?php echo $box->attr_name; ?>[image_size]" id="<?php echo $box->attr_for; ?>-image-size-custom"<?php checked( $box->image_size, 'cpac-custom' ); ?>><?php _e( 'Custom', CPAC_TEXTDOMAIN ); ?>
														</label>

														<label for="<?php echo $box->attr_for; ?>-image-size-w" class="custom-size-w<?php echo $box->image_size != 'cpac-custom' ? ' hidden' : ''; ?>">
															<input type="text" name="<?php echo $box->attr_name; ?>[image_size_w]" id="<?php echo $box->attr_for; ?>-image-size-w" value="<?php echo $box->image_size_w; ?>" /><?php _e( 'width', CPAC_TEXTDOMAIN ); ?>
														</label>
														<label for="<?php echo $box->attr_for; ?>-image-size-h" class="custom-size-h<?php echo $box->image_size != 'cpac-custom' ? ' hidden' : ''; ?>">
															<input type="text" name="<?php echo $box->attr_name; ?>[image_size_h]" id="<?php echo $box->attr_for; ?>-image-size-h" value="<?php echo $box->image_size_h; ?>" /><?php _e( 'height', CPAC_TEXTDOMAIN ); ?>
														</label>
													</div>
												</td>
											</tr>
											<?php endif;?>

											<?php if ( $box->is_field ) :  // is custom field ?>
											<tr class="column_before">
												<td class="label">
													<label for="<?php echo $box->attr_for; ?>-before"><?php _e("Before", CPAC_TEXTDOMAIN ); ?></label>
													<p class="description"><?php _e( 'This text will appear before the custom field value.', CPAC_TEXTDOMAIN ); ?></p>
												</td>
												<td class="input">
													<input type="text" class="cpac-before" name="<?php echo $box->attr_name; ?>[before]" id="<?php echo $box->attr_for; ?>-before" value="<?php echo $box->before; ?>"/>
												</td>
											</tr>
											<?php endif; ?>

											<?php if ( $box->is_field ) : // is custom field ?>
											<tr class="column_after">
												<td class="label">
													<label for="<?php echo $box->attr_for; ?>-after"><?php _e("After", CPAC_TEXTDOMAIN ); ?></label>
													<p class="description"><?php _e( 'This text will appear after the custom field value.', CPAC_TEXTDOMAIN ); ?></p>
												</td>
												<td class="input">
													<input type="text" class="cpac-after" name="<?php echo $box->attr_name; ?>[after]" id="<?php echo $box->attr_for; ?>-after" value="<?php echo $box->after; ?>"/>
												</td>
											</tr>
											<?php endif; ?>

											<?php if ( $box->enable_sorting && $licenses['sortable']->is_unlocked() ) : ?>
											<tr class="column_sorting">
												<td class="label">
													<label for="<?php echo $box->attr_for; ?>-sort-1"><?php _e("Enable sorting?", CPAC_TEXTDOMAIN ); ?></label>
													<p class="description"><?php _e( 'This will make the column support sorting.', CPAC_TEXTDOMAIN ); ?></p>
												</td>
												<td class="input">
													<label for="<?php echo $box->attr_for; ?>-sort-on">
														<input type="radio" value="on" name="<?php echo $box->attr_name; ?>[sort]" id="<?php echo $box->attr_for; ?>-sort-on"<?php checked( $box->sort, true ); ?>>
														<?php _e( 'Yes'); ?>
													</label>
													<label for="<?php echo $box->attr_for; ?>-sort-off">
														<input type="radio" value="off" name="<?php echo $box->attr_name; ?>[sort]" id="<?php echo $box->attr_for; ?>-sort-off"<?php checked( $box->sort, false ); ?>>
														<?php _e( 'No'); ?>
													</label>
												</td>
											</tr>
											<?php endif; ?>

											<?php if ( $box->enable_filtering ) : ?>
											<tr class="column_filtering">
												<td class="label">
													<label for="<?php echo $box->attr_for; ?>-filtering"><?php _e( 'Enable filtering?', CPAC_TEXTDOMAIN ); ?></label>
													<p class="description"><?php _e( 'This will add a dropdown for filtering.', CPAC_TEXTDOMAIN ); ?></p>
												</td>
												<td class="input">
													<label for="<?php echo $box->attr_for; ?>-filtering-on">
														<input type="radio" value="on" name="<?php echo $box->attr_name; ?>[filtering]" id="<?php echo $box->attr_for; ?>-filtering-on"<?php checked( $box->filtering, true ); ?>>
														<?php _e( 'Yes'); ?>
													</label>
													<label for="<?php echo $box->attr_for; ?>-filtering-off">
														<input type="radio" value="off" name="<?php echo $box->attr_name; ?>[filtering]" id="<?php echo $box->attr_for; ?>-filtering-off"<?php checked( $box->filtering, false ); ?>>
														<?php _e( 'No'); ?>
													</label>
												</td>
											</tr>
											<?php endif; ?>

											<?php if ( $box->is_field ) : // is custom field ?>
											<tr class="column_action">
												<td class="label">
												</td>
												<td class="input">
													<?php if ( 'column-meta-1' == $box->id ) : ?>
														<p class="remove-description description"><?php _e( 'This field can not be removed', CPAC_TEXTDOMAIN ); ?></p>
													<?php else : ?>
														<p><a href="javascript:;" class="cpac-delete-custom-field-box"><?php _e( 'Remove');?></a></p>
													<?php endif; ?>
												</td>
											</tr>
											<?php endif; ?>

											<?php do_action( 'cpac_column_fields', $box ); ?>

										</tbody>
									</table>
								</div><!--.column-form-->
							</div><!--.cpac-column-->

							<?php endforeach // get_column_boxes() ?>

						</div><!--.cpac-columns-->

						<div class="column-footer">
							<div class="order-message"><?php _e( 'Drag and drop to reorder', CPAC_TEXTDOMAIN ); ?></div>

							<div class="add-customfield-column-container">
							<?php if ( $type->get_meta_keys() ) : ?>
								<?php if ( $licenses['customfields']->is_unlocked() ) : ?>
									<a href="javascript:;" class="add-customfield-column button">+ <?php _e( 'Add Custom Field Column', CPAC_TEXTDOMAIN );?></a>
								<?php else : ?>
									<p><?php _e( 'Multipe Custom Fields is not activated.', CPAC_TEXTDOMAIN ); ?>
								<?php endif; ?>
							<?php else : ?>
								<p><?php _e( 'No meta data available', CPAC_TEXTDOMAIN ); ?>
							<?php endif; ?>
							</div><!--.a-right-->

						</div><!--.cpac-column-footer-->
					</div><!--.cpac-boxes-->
				</div><!--.columns-left-->
				<div class="clear"></div>

				</form>

			</div><!--.columns-container-->

			<?php endforeach; // get_types() ?>

			<div class="clear"></div>

		</div><!--.wrap-->
	<?php
	}

	/**
	 * General Settings.
	 *
	 * @since 1.0.0
	 */
	public function general_settings() {
		// addons
		$addons = array(
			'sortable'	=> array(
				'label'		=> __( 'Sortorder', CPAC_TEXTDOMAIN ),
				'license' 	=> new CPAC_Licence('sortable'),
				'more_link'	=> 'http://www.admincolumns.com/addons',
				'qtip'		=> "
					<p>" . __( 'This will make all of the new columns support sorting.', CPAC_TEXTDOMAIN ) . "</p>
					<p>" . __( 'By default WordPress let\'s you sort by title, date, comments and author. This will make you be able to <strong>sort by any column of any type!</strong>', CPAC_TEXTDOMAIN ) . "</p>
					<p>" . __( 'Perfect for sorting your articles, media files, comments, links and users', CPAC_TEXTDOMAIN ) . "</p>
					<p class='description'>" . __( '(columns that are added by other plugins are not supported)', CPAC_TEXTDOMAIN ) . "</p>
					<img src='" .  CPAC_URL . "/assets/images/addon_sortable_1.png' alt='' />
				"
			),
			'customfields'	=> array(
				'label'		=> __( 'Multiple Custom Fields', CPAC_TEXTDOMAIN ),
				'license' 	=> new CPAC_Licence( 'customfields' ),
				'more_link'	=> 'http://www.admincolumns.com/addons',
				'qtip'		=> "
					<p>" . __( 'Add as many Custom Fields columns as you want.', CPAC_TEXTDOMAIN ) . "</p>
					<p>" . __( 'It support custom fields from Posts, Media and Users.', CPAC_TEXTDOMAIN ) . "</p>
					<img src='" . CPAC_URL . "/assets/images/addon_multiplecustomfields.png' alt='' />
				"
			)
		);
	?>
	<div id="cpac" class="wrap">

		<?php screen_icon( CPAC_SLUG ); ?>
		<h2><?php _e( 'Admin Columns Settings', CPAC_TEXTDOMAIN ); ?></h2>

		<table class="form-table cpac-form-table">
			<tbody>
				<tr>
					<th scope="row">
						<h3><?php _e( 'Activate Add-ons', CPAC_TEXTDOMAIN ); ?></h3>
						<p><?php _e( 'Add-ons can be unlocked by purchasing a license key. Each key can be used on multiple sites.', CPAC_TEXTDOMAIN ); ?></p>
						<p><a href="http://www.admincolumns.com/addons/" target="_blank"><?php _e( 'Find Add-ons', CPAC_TEXTDOMAIN ); ?></a></p>
					</th>
					<td>
						<table class="widefat addons">
							<thead>
								<tr>
									<th class="activation_type"><?php _e( 'Addon', CPAC_TEXTDOMAIN ); ?></th>
									<th class="activation_status"><?php _e( 'Status', CPAC_TEXTDOMAIN ); ?></th>
									<th class="activation_code"><?php _e( 'Activation Code', CPAC_TEXTDOMAIN ); ?></th>
									<th class="activation_more"></th>
								</tr>
							</thead>
							<tbody>

								<?php foreach ( $addons as $id => $addon ) : ?>
								<tr id="activation-<?php echo $id; ?>">
									<td class="activation_type">
										<span class="cpac-pointer" rel="cpac-addon-instructions-<?php echo $id; ?>" data-pointer-position="bottom"><?php echo $addon['label']; ?></span>
										<div id="cpac-addon-instructions-<?php echo $id; ?>" style="display:none;">
											<h3><?php echo $addon['label']; ?></h3>
											<?php echo $addon['qtip']; ?>
										</div>
									</td>
									<td class="activation_status">
										<div class="activate<?php echo $addon['license']->is_unlocked() ? ' hidden' : ''; ?>">
											<?php _e( 'Inactive', CPAC_TEXTDOMAIN ); ?>
										</div>
										<div class="deactivate<?php echo $addon['license']->is_unlocked() ? '' : ' hidden'; ?>">
											<?php _e( 'Active', CPAC_TEXTDOMAIN ); ?>
									</td>
									<td class="activation_code">
										<div class="activate <?php echo $addon['license']->is_unlocked() ? ' hidden' : ''; ?>">
											<input type="text" placeholder="<?php _e( 'Fill in your activation code', CPAC_TEXTDOMAIN ) ?>" name="cpac-<?php echo $id; ?>-key">
											<a href="javascript:;" class="button"><?php _e( 'Activate', CPAC_TEXTDOMAIN ); ?><span></span></a>
										</div>
										<div class="deactivate<?php echo $addon['license']->is_unlocked() ? '' : ' hidden'; ?>">
											<span class="masked_key"><?php echo $addon['license']->get_masked_license_key(); ?></span>
											<a href="javascript:;" class="button"><?php _e( 'Deactivate', CPAC_TEXTDOMAIN ); ?><span></span></a>
										</div>
										<div class="activation-error-msg"></div>
									</td>
									<td class="activation_more">
										<a href="<?php echo $addon['more_link']; ?>" class="button-primary alignright" target="_blank"><?php _e( 'find out more', CPAC_TEXTDOMAIN ); ?> &raquo </a>
									</td>
								</tr>
								<?php endforeach; ?>

							</tbody>
						</table>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<h3><?php _e( 'Export Settings', CPAC_TEXTDOMAIN ); ?></h3>
						<p><?php _e( 'Pick the types for export from the left column. Click export to download your column settings.', CPAC_TEXTDOMAIN ); ?></p>
						<p><a href="javascript:;" class="cpac-pointer" rel="cpac-export-instructions-html"><?php _e( 'Instructions', CPAC_TEXTDOMAIN ); ?></a></p>
						<div id="cpac-export-instructions-html" style="display:none;">
							<h3><?php _e( 'Export Columns Types', CPAC_TEXTDOMAIN ); ?></h3>
							<p><?php _e( 'Instructions', CPAC_TEXTDOMAIN ); ?></p>
							<ol>
								<li><?php _e( 'Select one or more Column Types from the left section by clicking them.', CPAC_TEXTDOMAIN ); ?></li>
								<li><?php _e( 'Click export.', CPAC_TEXTDOMAIN ); ?></li>
								<li><?php _e( 'Save the export file when prompted.', CPAC_TEXTDOMAIN ); ?></li>
								<li><?php _e( 'Upload and import your settings file through Import Settings.', CPAC_TEXTDOMAIN ); ?></li>
							</ol>
						</div>
					</th>
					<td>
						<div class="cpac_export">
							<?php if ( $groups = $this->get_export_multiselect_options() ) : ?>
							<form method="post" action="">
								<?php wp_nonce_field( 'download-export', '_cpac_nonce' ); ?>
								<select name="export_types[]" multiple="multiple" class="select" id="cpac_export_types">
									<?php
									$labels = array(
										'general'	=> __( 'General', CPAC_TEXTDOMAIN ),
										'posts'		=> __( 'Posts', CPAC_TEXTDOMAIN )
									);
									?>
									<?php foreach ( $groups as $group_key => $group ) : ?>
									<optgroup label="<?php echo $labels[$group_key];?>">
										<?php foreach ( $group as $type ) : ?>
										<option value="<?php echo $type->storage_key; ?>"><?php echo $type->label; ?></option>
										<?php endforeach; ?>
									</optgroup>
									<?php endforeach; ?>
								</select>
								<a id="export-select-all" class="export-select" href="javascript:;"><?php _e( 'select all', CPAC_TEXTDOMAIN ); ?></a>
								<input type="submit" id="cpac_export_submit" class="button-primary alignright" value="<?php _e( 'Export', CPAC_TEXTDOMAIN ); ?>">
							</form>
							<?php else : ?>
								<?php _e( 'No saved data found.', CPAC_TEXDOMAIN ); ?>
							<?php endif; ?>
						</div>
					</td>
				</tr>
				<tr>
					<th scope="row">
						<h3><?php _e( 'Import Settings', CPAC_TEXTDOMAIN ); ?></h3>
						<p><?php _e( 'Copy and paste your import settings here.', CPAC_TEXTDOMAIN ); ?></p>
						<p><a href="javascript:;" class="cpac-pointer" rel="cpac-import-instructions-html"><?php _e( 'Instructions', CPAC_TEXTDOMAIN ); ?></a></p>
						<div id="cpac-import-instructions-html" style="display:none;">
							<h3><?php _e( 'Import Columns Types', CPAC_TEXTDOMAIN ); ?></h3>
							<p><?php _e( 'Instructions', CPAC_TEXTDOMAIN ); ?></p>
							<ol>
								<li><?php _e( 'Choose a Admin Columns Export file to upload.', CPAC_TEXTDOMAIN ); ?></li>
								<li><?php _e( 'Click upload file and import.', CPAC_TEXTDOMAIN ); ?></li>
								<li><?php _e( "That's it! You imported settings are now active.", CPAC_TEXTDOMAIN ); ?></li>
							</ol>
						</div>
					</th>
					<td class="padding-22">
						<div id="cpac_import_input">
							<form method="post" action="" enctype="multipart/form-data">
								<input type="file" size="25" name="import" id="upload">
								<?php wp_nonce_field( 'file-import', '_cpac_nonce' ); ?>
								<input type="submit" value="<?php _e( 'Upload file and import', CPAC_TEXTDOMAIN ); ?>" class="button" id="import-submit" name="file-submit">
							</form>
						</div>
					</td>
				</tr>
				<tr class="restore">
					<th scope="row">
						<h3><?php _e( 'Restore Settings', CPAC_TEXTDOMAIN ); ?></h3>
						<p><?php _e( 'This will delete all column settings and restore the default settings.', CPAC_TEXTDOMAIN ); ?></p>
					</th>
					<td class="padding-22">
						<form method="post" action="">
							<?php wp_nonce_field( 'restore-all','_cpac_nonce'); ?>
							<input type="hidden" name="cpac_action" value="restore_all" />
							<input type="submit" class="button" name="cpac-restore-defaults" value="<?php _e( 'Restore default settings', CPAC_TEXTDOMAIN ) ?>" onclick="return confirm('<?php _e("Warning! ALL saved admin columns data will be deleted. This cannot be undone. \'OK\' to delete, \'Cancel\' to stop", CPAC_TEXTDOMAIN ); ?>');" />
						</form>
					</td>
				</tr>
			</tbody>
		</table>

	</div>

	<?php
	}
}