<?php

class Cpac_Settings
{
	private $admin_page;

	function __construct()
	{
		// register settings
		add_action( 'admin_menu', array( $this, 'settings_menu') );
		add_action( 'admin_init', array( $this, 'register_settings') );
	}	

	/**
	 * Admin Menu.
	 *
	 * Create the admin menu link for the settings page.
	 *
	 * @since     1.0
	 */
	public function settings_menu()
	{
		$page = add_options_page(
			esc_html__( 'Admin Columns Settings', CPAC_TEXTDOMAIN ),  // Page title
			esc_html__( 'Admin Columns', CPAC_TEXTDOMAIN ), // Menu Title
			'manage_options', // Capability
			CPAC_SLUG, // Menu slug
			array( $this, 'plugin_settings_page')// Callback
		);

		// set admin page
		$this->admin_page = $page;

		// settings page specific styles and scripts
		add_action( "admin_print_styles-$page", array( $this, 'admin_styles') );
		add_action( "admin_print_scripts-$page", array( $this, 'admin_scripts') );

		// add help tabs
		add_action("load-$page", array( $this, 'help_tabs'));

		// handle requests gets a low priority so it will trigger when all other plugins have loaded their columns
		add_action( 'admin_init', array( $this, 'handle_requests' ), 1000 );

		// action ajax
		add_action( 'wp_ajax_cpac_addon_activation', array( $this, 'ajax_activation'));
	}

	/**
	 * Register admin css
	 *
	 * @since     1.0
	 */
	public function admin_styles()
	{
		wp_enqueue_style( 'wp-pointer' );
		wp_enqueue_style( 'jquery-ui-lightness', CPAC_URL.'/assets/ui-theme/jquery-ui-1.8.18.custom.css', array(), CPAC_VERSION, 'all' );
		wp_enqueue_style( 'cpac-admin', CPAC_URL.'/assets/css/admin-column.css', array(), CPAC_VERSION, 'all' );
	}

	/**
	 * Register admin scripts
	 *
	 * @since     1.0
	 */
	public function admin_scripts()
	{
		wp_enqueue_script( 'wp-pointer' );
		wp_enqueue_script( 'jquery-ui-slider' );
		wp_enqueue_script( 'cpac-qtip2', CPAC_URL.'/assets/js/jquery.qtip.js', array('jquery'), CPAC_VERSION );
		wp_enqueue_script( 'cpac-admin', CPAC_URL.'/assets/js/admin-column.js', array('jquery', 'dashboard', 'jquery-ui-sortable'), CPAC_VERSION );
	}

	/**
	 * Register plugin options
	 *
	 * @since     1.0
	 */
	public function register_settings()
	{
		// If we have no options in the database, let's add them now.
		if ( false === get_option('cpac_options') ) {
			add_option( 'cpac_options', apply_filters( 'cpac_default_plugin_options', array() ) );
		}

		register_setting( 'cpac-settings-group', 'cpac_options', array( $this, 'options_callback' ) );
	}

	/**
	 * Ajax activation
	 *
	 * @since     1.3.1
	 */
	public function ajax_activation()
	{
		// keys
		$key 	= $_POST['key'];
		$type 	= $_POST['type'];

		$licence = new cpac_licence( $type );

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
	 * @since     1.0
	 */
	public function handle_requests()
	{
		// only handle updates from the admin columns page
		if ( isset($_REQUEST['page']) && CPAC_SLUG == $_REQUEST['page'] ) {

			// Store default columns on 'updated settings'.
			// This needs to be fired on 'admin_init', before all additional plugin columns are loaded.
			// @todo: nonce security
			if ( ! empty($_REQUEST['settings-updated']) ) {
				$this->store_wp_default_columns();
			}

			// restore defaults
			if ( ! empty( $_POST['cpac-restore-defaults'] ) && wp_verify_nonce( $_POST['_cpac_restore_nonce'], 'restore' ) ) {
				$this->restore_defaults();
			}
		}
	}

	/**
	 * Stores WP default columns
	 *
	 * This will store columns that are set by WordPress core or theme
	 *
	 * @since     1.2
	 */
	private function store_wp_default_columns()
	{
		// stores the default columns that are set by WP or theme.
		$wp_default_columns = array();

		// Posts
		foreach ( cpac_utility::get_post_types() as $post_type ) {
			$type = new cpac_columns_posttype( $post_type );
			$wp_default_columns[$type->type] = $type->get_default_columns();
		}

		// Users
		$type = new cpac_columns_users();
		$wp_default_columns[$type->type] = $type->get_default_columns();

		// Media
		$type = new cpac_columns_media();
		$wp_default_columns[$type->type] = $type->get_default_columns();

		// Links
		$type = new cpac_columns_links();
		$wp_default_columns[$type->type] = $type->get_default_columns();

		// Comments
		$type = new cpac_columns_comments();
		$wp_default_columns[$type->type] = $type->get_default_columns();

		update_option( 'cpac_options_default', $wp_default_columns );
	}

	/**
	 * Restore defaults
	 *
	 * @since     1.0
	 */
	private function restore_defaults()
	{
		delete_option( 'cpac_options' );
		delete_option( 'cpac_options_default' );
	}

	/**
	 * Optional callback.
	 *
	 * @since     1.0
	 */
	public function options_callback($options)
	{
		return $options;
	}

	/**
	 * Add help tabs
	 *
	 * @since     1.3
	 */
	public function help_tabs( $page )
	{
		$screen = get_current_screen();

		if ( $screen->id != $this->admin_page || ! method_exists($screen,'add_help_tab') )
			return;

		$admin_url = get_admin_url();

		// add help content
		$tabs = array(
			array(
				'title'		=> 'Overview',
				'content'	=> "
					<h5>Codepress Admin Columns</h5>
					<p>
						This plugin is for adding and removing additional columns to the administration screens for post(types), pages, media library, comments, links and users. Change the column's label and reorder them.
					</p>
				"
			),
			array(
				'title'		=> 'Basics',
				'content'	=> "
					<h5>Show / Hide</h5>
					<p>
						You can switch columns on or off by cliking on the checkbox. This will show or hide each column heading.
					</p>
					<h5>Change order</h5>
					<p>
						By dragging the columns you can change the order which they will appear in.
					</p>
					<h5>Change label</h5>
					<p>
						By clicking on the triangle you will see the column options. Here you can change each label of the columns heading.
					</p>
					<h5>Change coluimn width</h5>
					<p>
						By clicking on the triangle you will see the column options. By using the draggable slider yo can set the width of the columns in percentages.
					</p>
				"
			),
			array(
				'title'		=> 'Custom Field',
				'content'	=> "
					<h5>'Custom Field' column</h5>
					<p>
						The custom field colum uses the custom fields from posts and users. There are 8 types which you can set.
					</p>
					<ul>
						<li><strong>Default</strong><br/>Value: Can be either a string or array. Arrays will be flattened and values are seperated by a ',' comma.</li>
						<li><strong>Image</strong><br/>Value: should only contain an image URL.</li>
						<li><strong>Media Library Icon</strong><br/>Value: should only contain Attachment IDs ( seperated by ',' ).</li>
						<li><strong>Excerpt</strong><br/>Value: This will show the first 20 words of the Post content.</li>
						<li><strong>Multiple Values</strong><br/>Value: should be an array. This will flatten any ( multi dimensional ) array.</li>
						<li><strong>Numeric</strong><br/>Value: Integers only.<br/>If you have the 'sorting addon' this will be used for sorting, so you can sort your posts on numeric (custom field) values.</li>
						<li><strong>Date</strong><br/>Value: Can be unix time stamp of date format as described in the <a href='http://codex.wordpress.org/Formatting_Date_and_Time'>Codex</a>. You can change the outputted date format at the <a href='{$admin_url}options-general.php'>general settings</a> page.</li>
						<li><strong>Post Titles</strong><br/>Value: can be one or more Post ID's (seperated by ',').</li>
					</ul>
				"
			)
		);

		foreach ( $tabs as $k => $tab ) {
			$screen->add_help_tab(array(
				'id'		=> 'cpac-tab-'.$k, 	// unique id
				'title'		=> $tab['title'],	// label
				'content'	=> $tab['content'], // body
			));
		}
	}

	/**
	 * Checks if menu type is currently viewed
	 *
	 * @since     1.0
	 */
	function is_menu_type_current( $type )
	{
		// referer
		$referer = ! empty($_REQUEST['cpac_type']) ? $_REQUEST['cpac_type'] : '';

		// get label
		$clean_label = cpac_utility::sanitize_string( $type );

		// get first element from post-types
		$first 		= array_shift( array_values( cpac_utility::get_post_types() ) );

		// display the page that was being viewed before saving
		if ( $referer ) {
			if ( $referer == 'cpac-box-'.$clean_label ) {
				return true;
			}

		// settings page has not yet been saved
		} elseif ( $first == $type  ) {
			return true;
		}

		return false;
	}

	/**
	 * Settings Page Template.
	 *
	 * This function in conjunction with others uses the WordPress
	 * Settings API to create a settings page where users can adjust
	 * the behaviour of this plugin.
	 *
	 * @since     1.0
	 */
	public function plugin_settings_page()
	{
		// external urls
		$urls = array(
			'codepress'	=> 'http://www.codepress.nl/plugins/codepress-admin-columns',
			'plugins'	=> 'http://wordpress.org/extend/plugins/codepress-admin-columns',
			'wordpress'	=> 'http://wordpress.org/tags/codepress-admin-columns'
		);

		$class_current_settings = $this->is_menu_type_current('plugin_settings') ? ' current' : ' hidden';

		/** Sortable */
		$masked_key 				= '';
		$class_sortorder_activate 	= '';
		$class_sortorder_deactivate = ' hidden';

		// is unlocked
		$licence = new cpac_licence('sortable');

		if ( $licence->is_unlocked() ) {
			$masked_key 	 = $licence->get_masked_license_key();
			$class_sortorder_activate = ' hidden';
			$class_sortorder_deactivate = '';
		}

		// find out more
		$find_out_more = "<a href='{$urls['codepress']}/sortorder-addon/' class='button-primary alignright' target='_blank'>".__('find out more', CPAC_TEXTDOMAIN)." &raquo</a>";

		// addons
		$addons = "
			<tr>
				<td colspan='2'>
					<h2>".__('Activate Add-ons', CPAC_TEXTDOMAIN)."</h2>
					<p>".__('Add-ons can be unlocked by purchasing a license key. Each key can be used on multiple sites', CPAC_TEXTDOMAIN)." <a target='_blank' href='{$urls['codepress']}/sortorder-addon/'>Visit the Plugin Store</a>.</p>
					<table class='widefat addons'>
						<thead>
							<tr>
								<th class='activation_type'>".__('Addon', CPAC_TEXTDOMAIN)."</th>
								<th class='activation_status'>".__('Status', CPAC_TEXTDOMAIN)."</th>
								<th class='activation_code'>".__('Activation Code', CPAC_TEXTDOMAIN)."</th>
								<th class='activation_more'></th>
							</tr>
						</thead>
						<tbody>
							<tr id='cpac-activation-sortable' class='last'>
								<td class='activation_type'>
									<span>" . __('Sortorder', CPAC_TEXTDOMAIN) . "</span>
									<div class='cpac-tooltip hidden'>
										<div class='qtip_title'>" . __('Sortorder', CPAC_TEXTDOMAIN) . "</div>
										<div class='qtip_content'>
											<p>".__('This will make all of the new columns support sorting', CPAC_TEXTDOMAIN).".</p>
											<p>".__('By default WordPress let\'s you sort by title, date, comments and author. This will make you be able to <strong>sort by any column of any type!</strong>', CPAC_TEXTDOMAIN)."</p>
											<p>".__('Perfect for sorting your articles, media files, comments, links and users', CPAC_TEXTDOMAIN).".</p>
											<p class='description'>".__('(columns that are added by other plugins are not supported)', CPAC_TEXTDOMAIN).".</p>
											<img src='" . CPAC_URL.'/assets/images/addon_sortable_1.png' . "' alt='' />
											{$find_out_more}
										</div>
									</div>
								</td>
								<td class='activation_status'>
									<div class='activate{$class_sortorder_activate}'>
										" . __('Inactive', CPAC_TEXTDOMAIN) . "
									</div>
									<div class='deactivate{$class_sortorder_deactivate}'>
										" . __('Active', CPAC_TEXTDOMAIN) . "
									</div>
								</td>
								<td class='activation_code'>
									<div class='activate{$class_sortorder_activate}'>
										<input type='text' value='" . __('Fill in your activation code', CPAC_TEXTDOMAIN) . "' name='cpac-sortable-key'>
										<a href='javascript:;' class='button'>" . __('Activate', CPAC_TEXTDOMAIN) . "<span></span></a>
									</div>
									<div class='deactivate{$class_sortorder_deactivate}'>
										<span class='masked_key'>{$masked_key}</span>
										<a href='javascript:;' class='button'>" . __('Deactivate', CPAC_TEXTDOMAIN) . "<span></span></a>
									</div>
									<div class='activation-error-msg'></div>
								</td>
								<td class='activation_more'>{$find_out_more}</td>
							</tr><!-- #cpac-activation-sortable -->
						</tbody>
					</table>
					<div class='addon-translation-string hidden'>
						<span class='tstring-fill-in'>" . __('Enter your activation code', CPAC_TEXTDOMAIN) . "</span>
						<span class='tstring-unrecognised'>" . __('Activation code unrecognised', CPAC_TEXTDOMAIN) . "</span>
					</div>
				</td>
			</tr>
		";

		// import / export
		$export_selections = array();
		foreach ( cpac_utility::get_types() as $type ) {
			$export_selections[] = "<option value='{$type->type}'>" . $type->get_label() . "</option>";
		}

		$export_import = "
			<tr>
				<td class='first-col'>
					<h2>" . __('Export Settings.', CPAC_TEXTDOMAIN ) . "</h2>
					<p>" . __('You this export to migrate your admin column settings from one WordPress site to another.', CPAC_TEXTDOMAIN ) . ":</p>
					<p><a href='#' class='cpac-pointer' rel='cpac-import-instructions-html'>" . __('Instructions', CPAC_TEXTDOMAIN ) . "</a></p>
					<div id='cpac-import-instructions-html' style='display:none;'>
						<h3>" . __('Import Columns Types', CPAC_TEXTDOMAIN ) . "</h3>
						<p>" . __('Instructions', CPAC_TEXTDOMAIN ) . ":</p>
						<ol>
							<li>" . __('Select one or more types.', CPAC_TEXTDOMAIN ) . "</li>
							<li>" . __('Click Export.', CPAC_TEXTDOMAIN ) . "</li>
							<li>" . __('Copy the generated code to your clipboard.', CPAC_TEXTDOMAIN ) . "</li>
							<li>" . __('Go to you other site and paste it under Import Settings.', CPAC_TEXTDOMAIN ) . "</li>
						</ol>
					</div>
				</td>
				<td>
					<div class='cpac_export'>
						<select size='" . count($export_selections) . "' multiple='multiple' class='select' id='cpac_export_types'>
							" . implode( $export_selections ) . "
						</select>
						<br/>
						<a id='cpac_export_submit' class='button' href='javascript:;'>" . __('Export', CPAC_TEXTDOMAIN ) . "<span></span></a>
						<div class='export-message'></div>
					</div>
					<div id='cpac_export_output'>
						<textarea rows='" . count($export_selections) . "'></textarea>
						<p class='description'>" . __('Copy the following code to your clipboard. Then you can use this code for importing.', CPAC_TEXTDOMAIN ) . "</p>
					</div>
				</td>
			</tr>
			<tr class='last'>
				<td class='first-col'>
					<h2>" . __('Import settings', CPAC_TEXTDOMAIN ) . "</h2>
					<p>" . __('Copy and paste your import settings here.', CPAC_TEXTDOMAIN ) . "</p>
				</td>
				<td>
					<div id='cpac_import_input'>
						<textarea rows='10'></textarea>
						<a id='cpac_import_submit' class='button' href='javascript:;'>" . __('Import', CPAC_TEXTDOMAIN ) . "<span></span></a>
						<div class='import-message'></div>
					</div>
				</td>
			</tr>
		";

		// general options
		$general_options = "
			<!--
			<tr class='last'>
				<td colspan='2'>
					<h2>Options</h2>
					<ul class='cpac-options'>
						<li>
							<div class='cpac-option-label'>Thumbnail size</div>
							<div class='cpac-option-inputs'>
								<input type='text' id='thumbnail_size_w' class='small-text' name='cpac_options[settings][thumb_width]' value='80'/>
								<label for='thumbnail_size_w'>Width</label>
								<br/>
								<input type='text' id='thumbnail_size_h' class='small-text' name='cpac_options[settings][thumb_height]' value='80'/>
								<label for='thumbnail_size_h'>Height</label>
							</div>
						</li>
						<li>
							<div class='cpac-option-label'>Excerpt length</div>
							<div class='cpac-option-inputs'>

								<input type='text' id='excerpt_length' class='small-text' name='cpac_options[settings][excerpt_length]' value='15'/>
								<label for='excerpt_length'>Number of words</label>
							</div>
						</li>
					</ul>
				</td>
			</tr>
			-->
		";

		// set
		$menu 	= '';
		$count 	= 1;

		// referer
		$referer = ! empty($_REQUEST['cpac_type']) ? $_REQUEST['cpac_type'] : '';

		// loop
		foreach ( cpac_utility::get_types() as $type ) {

			$label 		 = $type->get_label();
			$clean_label = cpac_utility::sanitize_string($type->type);

			// divider
			$divider 	= $count++ == 1 ? '' : ' | ';

			// current
			$current = '';
			if ( $this->is_menu_type_current($type->type) )
				$current = ' class="current"';

			// menu list
			$menu .= "
				<li>{$divider}<a{$current} href='#cpac-box-{$clean_label}'>{$label}</a></li>
			";
		}

		// settings url
		$class_current = $this->is_menu_type_current('plugin_settings') ? ' current': '';

		// Help screen message
		$help_text = '';
		if ( version_compare( get_bloginfo('version'), '3.2', '>' ) )
			$help_text = '<p>'.__('You will find a short overview at the <strong>Help</strong> section in the top-right screen.', CPAC_TEXTDOMAIN).'</p>';

		// find out more
		$find_out_more = "<a href='{$urls['codepress']}/sortorder-addon/' class='alignright green' target='_blank'>".__('find out more', CPAC_TEXTDOMAIN)." &raquo</a>";

	?>
		<div id="cpac" class="wrap">
			<?php screen_icon(CPAC_SLUG) ?>
			<h2><?php _e('Codepress Admin Columns', CPAC_TEXTDOMAIN); ?></h2>
			
			<div class="cpac-menu">
				<ul class="subsubsub">
					<?php echo $menu ?>
				</ul>
				<a href="#cpac-box-plugin_settings" class="cpac-settings-link<?php echo $class_current?>"><?php _e('Settings / Addons', CPAC_TEXTDOMAIN) ?></a>
			</div>
			
			<div id="cpac-columns">
				
				<div id="cpac-columns-right">
					
					<?php foreach ( cpac_utility::get_types() as $type ) : ?>
					
					<div class="form-actions" data-type="<?php echo $type->type ?>">						
						<a href="" class="reset-column-type"><?php _e('Reset', CPAC_TEXTDOMAIN); ?> <?php echo $type->get_label(); ?></a>						
						<input class="button-primary submit-update" type="submit" form="cpac-submit-<?php echo $type->type; ?>" value="<?php _e('Update') ?>" accesskey="p" >
					</div>
					
					<?php endforeach; ?>
					
				</div><!--cpac-columns-right-->
				
				<div id="cpac-columns-left">
				
				<?php foreach ( cpac_utility::get_types() as $type ) : ?>
					
					<div id="cpac-box-<?php echo cpac_utility::sanitize_string($type->type); ?>" class="cpac-boxes<?php echo $this->is_menu_type_current( $type->type ) ? ' current' : ' hidden'; ?>">
						
						<h2><?php echo $type->get_label(); ?></h2>
						
						<form id="cpac-submit-<?php echo cpac_utility::sanitize_string($type->type); ?>" action="" method="post">
						
						<div class="cpac-columns">
							
							<?php foreach ( $type->get_column_boxes() as $box ) : ?>
							
							<div class="cpac-column<?php $box->state ? 'active' : ''; ?>">
								<div class="column-meta">
									<table class="widefat">
										<tbody>
											<tr>
												<td class="column_sort"></td>
												<td class="column_status"></td>
												<td class="column_label"><?php echo $box->label; ?></td>
												<td class="column_type"><?php echo $box->type_label; ?></td>
												<td class="column_edit">
													<a href="javascript:;"></a>
												</td>									
											</tr>
										</tbody>
									</table>
								</div><!--.column-meta-->
								
								<div class="column-form">
									<table class="widefat">
										<tbody>
											<tr class="column_label<?php echo $box->hide_options ? ' hidden' : '' ?>">
												<td class="label">
													<label for="<?php echo $box->attr_for; ?>-label"><?php _e('Label', CPAC_TEXTDOMAIN);?></label>
													<p class="description"><?php _e('This is the name which will appear on the EDIT page', CPAC_TEXTDOMAIN ); ?></p>
												</td>
												<td>
													<input type="text" name="<?php echo $box->attr_name; ?>[label]" id="<?php echo $box->attr_for; ?>-label" value="<?php echo $box->label ?>" class="text"/>				
												</td>								
											</tr>
											<tr class="column_label">
												<td class="label">													
													<label for="<?php echo $box->attr_for; ?>-width"><?php _e("Width", CPAC_TEXTDOMAIN); ?></label>
													
												</td>
												<td>
													<div class="description width-decription" title="<?php _e('default', CPAC_TEXTDOMAIN); ?>">
														<?php echo $box->width_descr; ?>
													</div>
													<div class="input-width-range"></div>
													<input type="hidden" maxlength="4" class="input-width" name="<?php echo $box->attr_name; ?>[width]" id="<?php echo $box->attr_for; ?>-width" value="<?php echo $box->width; ?>" />
												</td>								
											</tr>											
										</tbody>									
									</table>
								</div><!--.column-form-->
							</div><!--.cpac-column-->
							
							<?php endforeach // get_column_boxes() ?>
							
						</div><!--.cpac-columns-->
						
						<div class="column-footer">
						
							<div class="order-message"><?php _e( 'Drag and drop to reorder', CPAC_TEXTDOMAIN ); ?></div>
							
							<?php if ( true || $type->get_meta_keys() ) : ?>
								<a href="javacript:;" class="add-customfield-column button">+ <?php _e('Add Custom Field Column', CPAC_TEXTDOMAIN);?></a>
							<?php endif; ?>
							
						</div><!--.cpac-column-footer-->
						
						</form>						
						
					</div><!--.cpac-boxes-->
					
					<?php endforeach; // get_types() ?>
					
				</div><!--cpac-columns-left-->
				
			</div><!--cpac-columns-->

			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			<!--
						<ul class="cpac-option-list">
						
							<?php foreach ( $type->get_column_boxes() as $box ) : ?>

							<li class="<?php echo $box->classes ?>">
								<div class="cpac-sort-handle"></div>
								<div class="cpac-type-options">
									<div class="cpac-checkbox"></div>
									<input type="hidden" class="cpac-state" name="<?php echo $box->attr_name; ?>[state]" value="<?php echo $box->state; ?>"/>
									<label class="main-label"><?php echo $box->label; ?></label>
								</div>
								<div class="cpac-meta-title">
									<a class="cpac-action" href="#open">open</a>
									<span><?php echo $box->type_label; ?></span>
								</div>
								<div class="cpac-type-inside">
									<label for="<?php echo $box->attr_for; ?>-label"<?php echo $box->hide_options ? ' style="display:none"' : '' ?>>Label: </label>
									<input type="text" name="<?php echo $box->attr_name; ?>[label]" id="<?php echo $box->attr_for; ?>-label" value="<?php echo $box->label ?>" class="text"<?php echo $box->hide_options ? ' style="display:none"' : '' ?>/>
									<label for="<?php echo $box->attr_for; ?>-width"><?php _e("Width", CPAC_TEXTDOMAIN); ?>:</label>
									<input type="hidden" maxlength="4" class="input-width" name="<?php echo $box->attr_name; ?>[width]" id="<?php echo $box->attr_for; ?>-width" value="<?php echo $box->width; ?>" />
									<div class="description width-decription" title="<?php _e('default', CPAC_TEXTDOMAIN); ?>">
										<?php echo $box->width_descr; ?>
									</div>
									<div class="input-width-range"></div>
									<br/>
									
									<?php 
									// Custom Fields
									if ( isset( $box->field ) ) : ?>

									<label for="<?php echo $box->attr_for; ?>-field"><?php _e("Custom Field", CPAC_TEXTDOMAIN) ?>: </label>
									<select name="<?php echo $box->attr_name; ?>[field]" id="<?php echo $box->attr_for; ?>-field">

									<?php foreach ( $box->fields as $field ) : ?>
										<option value="<?php echo $field ?>"<?php selected( $field, $box->field ) ?>><?php echo substr($field,0,10) == "cpachidden" ? str_replace('cpachidden','',$field) : $field; ?></option>
									<?php endforeach; ?>

									</select>
									<br/>
									<label for="<?php echo $box->attr_for; ?>-field_type"><?php _e("Field Type", CPAC_TEXTDOMAIN); ?>: </label>
									<select name="<?php echo $box->attr_name; ?>[field_type]" id="<?php echo $box->attr_for; ?>-field_type">

									<?php
									// Custom Field Types
									foreach ( $type->get_custom_field_types() as $fieldkey => $fieldtype ) : ?>
										<option value="<?php echo $fieldkey ?>"<?php selected( $fieldkey, $box->field_type ) ?>><?php echo $fieldtype; ?></option>
									<?php endforeach; ?>

									</select>
									<br/>
									<label for="<?php echo $box->attr_for; ?>-before"><?php _e("Before", CPAC_TEXTDOMAIN); ?>: </label>
									<input type="text" class="cpac-before" name="<?php echo $box->attr_name; ?>[before]" id="<?php echo $box->attr_for; ?>-before" value="<?php echo $box->before; ?>"/>
									<br/>
									<label for="<?php echo $box->attr_for; ?>-after"><?php _e("After", CPAC_TEXTDOMAIN); ?>: </label>
									<input type="text" class="cpac-after" name="<?php echo $box->attr_name; ?>[after]" id="<?php echo $box->attr_for; ?>-after" value="<?php echo $box->after; ?>"/>
									<br/>
									<?php if ( 'column-meta-1' == $box->id ) : ?>
										<p class="remove-description description"><?php _e('This field can not be removed', CPAC_TEXTDOMAIN); ?></p>
									<?php else : ?>
										<p><a href="javascript:;" class="cpac-delete-custom-field-box"><?php _e('Remove');?></a></p>
									<?php endif; ?>

								<?php
								// Authorname Types
								elseif ( 'column-author-name' == $box->id ) : ?>
									<label for="<?php echo $box->attr_for; ?>-display_as"><?php _e("Display name as", CPAC_TEXTDOMAIN); ?>: </label>
									<select name="<?php echo $box->attr_name; ?>[display_as]" id="<?php echo $box->attr_for; ?>-display_as">
									<?php foreach ( $type->get_authorname_types() as $authortype => $authorlabel ) : ?>
										<option value=""<?php selected( $authortype, $box->display_as )?>><?php echo $authorlabel ?></option>
									<?php endforeach; ?>
									</select>
								<?php endif; ?>

								</div>
							</li>

							<?php endforeach ?>

						</ul>
						<?php if ( $type->get_meta_keys() ) : ?>
							<a href="javacript:;" class="cpac-add-customfield-column button">+ <?php _e('Add Custom Field Column', CPAC_TEXTDOMAIN);?></a>
						<?php endif; ?>
			-->
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			<div class="postbox-container cpac-col-right">
				<div class="metabox-holder">
					<div class="meta-box-sortables">

						<div id="addons-cpac-settings" class="postbox">
							<div title="Click to toggle" class="handlediv"><br></div>
							<h3 class="hndle">
								<span><?php _e('Get the Addon', CPAC_TEXTDOMAIN) ?></span>
							</h3>
							<div class="inside">
								<p><?php _e('By default WordPress let\'s you only sort by title, date, comments and author.', CPAC_TEXTDOMAIN) ?></p>
								<p><?php _e('Make <strong>all columns</strong> of <strong>all types</strong> within the plugin support sorting &#8212; with the sorting addon.', CPAC_TEXTDOMAIN) ?></p>
								<p class="description"><?php _e('(columns that are added by other plugins are not supported)', CPAC_TEXTDOMAIN) ?>.</p>
								<?php echo $find_out_more ?>
							</div>
						</div><!-- addons-cpac-settings -->

						<div id="likethisplugin-cpac-settings" class="postbox">
							<div title="Click to toggle" class="handlediv"><br></div>
							<h3 class="hndle">
								<span><?php _e('Like this plugin?', CPAC_TEXTDOMAIN) ?></span>
							</h3>
							<div class="inside">
								<p><?php _e('Why not do any or all of the following', CPAC_TEXTDOMAIN) ?>:</p>
								<ul>
									<li><a href="<?php echo $urls['plugins'] ?>"><?php _e('Give it a 5 star rating on WordPress.org.', CPAC_TEXTDOMAIN) ?></a></li>
									<li><a href="<?php echo $urls['codepress'] ?>/"><?php _e('Link to it so other folks can find out about it.', CPAC_TEXTDOMAIN) ?></a></li>
									<li class="donate_link"><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=ZDZRSYLQ4Z76J"><?php _e('Donate a token of your appreciation.', CPAC_TEXTDOMAIN) ?></a></li>
								</ul>
							</div>
						</div><!-- likethisplugin-cpac-settings -->

						<div id="latest-news-cpac-settings" class="postbox">
							<div title="Click to toggle" class="handlediv"><br></div>
							<h3 class="hndle">
								<span><?php _e('Follow us', CPAC_TEXTDOMAIN) ?></span>
							</h3>
							<div class="inside">
								<ul>
									<li class="twitter"><a href="http://twitter.com/codepressNL"><?php _e('Follow Codepress on Twitter.', CPAC_TEXTDOMAIN) ?></a></li>
									<li class="facebook"><a href="https://www.facebook.com/codepressNL"><?php _e('Like Codepress on Facebook.', CPAC_TEXTDOMAIN) ?></a></li>

								</ul>
							</div>
						</div><!-- latest-news-cpac-settings -->

						<div id="side-cpac-settings" class="postbox">
							<div title="Click to toggle" class="handlediv"><br></div>
							<h3 class="hndle">
								<span><?php _e('Need support?', CPAC_TEXTDOMAIN) ?></span>
							</h3>
							<div class="inside">
								<?php echo $help_text ?>
								<p><?php printf(__('If you are having problems with this plugin, please talk about them in the <a href="%s">Support forums</a> or send me an email %s.', CPAC_TEXTDOMAIN), $urls['wordpress'], '<a href="mailto:info@codepress.nl">info@codepress.nl</a>' );?></p>
								<p><?php printf(__("If you're sure you've found a bug, or have a feature request, please <a href='%s'>submit your feedback</a>.", CPAC_TEXTDOMAIN), "{$urls['codepress']}/feedback");?></p>
							</div>
						</div><!-- side-cpac-settings -->

					</div>
				</div>
			</div><!-- .postbox-container -->

			<div class="postbox-container cpac-col-left">
				<div class="metabox-holder">
					<div class="meta-box-sortables">

						<div id="general-cpac-settings" class="postbox">
							<div title="Click to toggle" class="handlediv"><br></div>
							<h3 class="hndle">
								<span><?php _e('Admin Columns', CPAC_TEXTDOMAIN ); ?></span>
							</h3>
							<div class="inside">
								<form method="post" action="options.php">

								<?php settings_fields( 'cpac-settings-group' ); ?>

								<table class="form-table">

									<?php
									foreach ( cpac_utility::get_types() as $type ) :
										$class = $this->is_menu_type_current( $type->type ) ? ' current' : ' hidden';
									?>

									<tr id="cpac-box-<?php echo cpac_utility::sanitize_string($type->type); ?>" valign="top" class="cpac-box-row<?php echo $class?>">
										<th class="cpac_post_type" scope="row">
											<?php echo $type->get_label(); ?>
										</th>
										<td>
											<h3 class="cpac_post_type hidden">
												<?php echo $type->get_label(); ?>
											</h3>

											<div class="cpac-box">
												<ul class="cpac-option-list">

													<?php foreach ( $type->get_column_boxes() as $box ) : ?>

													<li class="">
														<div class="cpac-sort-handle"></div>
														<div class="cpac-type-options">
															<div class="cpac-checkbox"></div>
															<input type="hidden" class="cpac-state" name="<?php echo $box->attr_name; ?>[state]" value="<?php echo $box->state; ?>"/>
															<label class="main-label"><?php echo $box->label; ?></label>
														</div>
														<div class="cpac-meta-title">
															<a class="cpac-action" href="#open">open</a>
															<span><?php echo $box->type_label; ?></span>
														</div>
														<div class="cpac-type-inside">
															<label for="<?php echo $box->attr_for; ?>-label"<?php echo $box->hide_options ? ' style="display:none"' : '' ?>>Label: </label>
															<input type="text" name="<?php echo $box->attr_name; ?>[label]" id="<?php echo $box->attr_for; ?>-label" value="<?php echo $box->label ?>" class="text"<?php echo $box->hide_options ? ' style="display:none"' : '' ?>/>
															<label for="<?php echo $box->attr_for; ?>-width"><?php _e("Width", CPAC_TEXTDOMAIN); ?>:</label>
															<input type="hidden" maxlength="4" class="input-width" name="<?php echo $box->attr_name; ?>[width]" id="<?php echo $box->attr_for; ?>-width" value="<?php echo $box->width; ?>" />
															<div class="description width-decription" title="<?php _e('default', CPAC_TEXTDOMAIN); ?>">
																<?php echo $box->width_descr; ?>
															</div>
															<div class="input-width-range"></div>
															<br/>
															
															<?php 
															// Custom Fields
															if ( isset( $box->field ) ) : ?>

															<label for="<?php echo $box->attr_for; ?>-field"><?php _e("Custom Field", CPAC_TEXTDOMAIN) ?>: </label>
															<select name="<?php echo $box->attr_name; ?>[field]" id="<?php echo $box->attr_for; ?>-field">

															<?php foreach ( $box->fields as $field ) : ?>
																<option value="<?php echo $field ?>"<?php selected( $field, $box->field ) ?>><?php echo substr($field,0,10) == "cpachidden" ? str_replace('cpachidden','',$field) : $field; ?></option>
															<?php endforeach; ?>

															</select>
															<br/>
															<label for="<?php echo $box->attr_for; ?>-field_type"><?php _e("Field Type", CPAC_TEXTDOMAIN); ?>: </label>
															<select name="<?php echo $box->attr_name; ?>[field_type]" id="<?php echo $box->attr_for; ?>-field_type">

															<?php
															// Custom Field Types
															foreach ( $type->get_custom_field_types() as $fieldkey => $fieldtype ) : ?>
																<option value="<?php echo $fieldkey ?>"<?php selected( $fieldkey, $box->field_type ) ?>><?php echo $fieldtype; ?></option>
															<?php endforeach; ?>

															</select>
															<br/>
															<label for="<?php echo $box->attr_for; ?>-before"><?php _e("Before", CPAC_TEXTDOMAIN); ?>: </label>
															<input type="text" class="cpac-before" name="<?php echo $box->attr_name; ?>[before]" id="<?php echo $box->attr_for; ?>-before" value="<?php echo $box->before; ?>"/>
															<br/>
															<label for="<?php echo $box->attr_for; ?>-after"><?php _e("After", CPAC_TEXTDOMAIN); ?>: </label>
															<input type="text" class="cpac-after" name="<?php echo $box->attr_name; ?>[after]" id="<?php echo $box->attr_for; ?>-after" value="<?php echo $box->after; ?>"/>
															<br/>
															<?php if ( 'column-meta-1' == $box->id ) : ?>
																<p class="remove-description description"><?php _e('This field can not be removed', CPAC_TEXTDOMAIN); ?></p>
															<?php else : ?>
																<p><a href="javascript:;" class="cpac-delete-custom-field-box"><?php _e('Remove');?></a></p>
															<?php endif; ?>

														<?php
														// Authorname Types
														elseif ( 'column-author-name' == $box->id ) : ?>
															<label for="<?php echo $box->attr_for; ?>-display_as"><?php _e("Display name as", CPAC_TEXTDOMAIN); ?>: </label>
															<select name="<?php echo $box->attr_name; ?>[display_as]" id="<?php echo $box->attr_for; ?>-display_as">
															<?php foreach ( $type->get_authorname_types() as $authortype => $authorlabel ) : ?>
																<option value=""<?php selected( $authortype, $box->display_as )?>><?php echo $authorlabel ?></option>
															<?php endforeach; ?>
															</select>
														<?php endif; ?>

														</div>
													</li>

													<?php endforeach ?>

												</ul>
												<?php if ( $type->get_meta_keys() ) : ?>
													<a href="javacript:;" class="cpac-add-customfield-column button">+ <?php _e('Add Custom Field Column', CPAC_TEXTDOMAIN);?></a>
												<?php endif; ?>
												<div class="cpac-reorder-msg"><?php _e('drag and drop to reorder', CPAC_TEXTDOMAIN) ?></div>
											</div>
										</td>
									</tr>

									<?php endforeach; ?>

									<tr id="cpac-box-plugin_settings" valign="top" class="cpac-box-row <?php echo $class_current_settings ?>">
										<td colspan="2">
											<table class="nopadding">
											 <?php echo $addons ?>
											 <?php echo $export_import ?>
											 <?php echo $general_options ?>
											</table>
										</td>
									</tr><!-- #cpac-box-plugin_settings -->

									<tr class="bottom" valign="top">
										<th scope="row"></th>
										<td>
											<p class="submit">
												<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
											</p>
										</td>
									</tr>
								</table>
								</form>
							</div>
						</div><!-- general-settings -->

						<div id="restore-cpac-settings" class="postbox">
							<div title="Click to toggle" class="handlediv"><br></div>
							<h3 class="hndle">
								<span><?php _e('Restore defaults', CPAC_TEXTDOMAIN) ?></span>
							</h3>
							<div class="inside">
								<form method="post" action="">
									<?php wp_nonce_field( 'restore','_cpac_restore_nonce'); ?>
									<input type="submit" class="button" name="cpac-restore-defaults" value="<?php _e('Restore default settings', CPAC_TEXTDOMAIN ) ?>" onclick="return confirm('<?php _e("Warning! ALL saved admin columns data will be deleted. This cannot be undone. \'OK\' to delete, \'Cancel\' to stop", CPAC_TEXTDOMAIN); ?>');" />
								</form>
								<p class="description"><?php _e('This will delete all column settings and restore the default settings.', CPAC_TEXTDOMAIN); ?></p>
							</div>
						</div><!-- restore-cpac-settings -->

					</div>
				</div>
			</div><!-- .postbox-container -->
		</div>
	<?php
	}
}

?>