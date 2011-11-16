<?php

/* 
To do:
- add hooks/filters
- ajax adding of columns 
- OOP
- request orderby filtering

class adminColumns

	// methods
	function __construct() {}
	function get() {}
	function get() {}
*/

/**
 * Admin Menu.
 *
 * Create the admin menu link for the settings page.
 *
 * @access    private
 * @since     0.1
 */
function cpac_settings_menu() 
{
	$page = add_options_page(
		esc_html__( 'Admin Columns Settings', 'cpac' ), /* HTML <title> tag. */
		esc_html__( 'Admin Columns', 'cpac' ), /* Link text in admin menu. */
		'manage_options',
		'cpac_plugin_settings',
		'cpac_plugin_settings_page'
		);
	
	// handle file upload
	add_action("load-$page", 'cpac_handle_requests', 10);
	
	// css scripts
	add_action( "admin_print_styles-$page", 'cpac_admin_styles' );
}
add_action('admin_menu', 'cpac_settings_menu');

/**
 * Settings Page Template.
 *
 * This function in conjunction with others usei the WordPress
 * Settings API to create a settings page where users can adjust
 * the behaviour of this plugin. 
 *
 * @access    private
 * @since     0.1
 */
function cpac_plugin_settings_page() 
{
	// loop through post types
	$rows = '';
	foreach ( cpac_get_post_types() as $post_type ) {
		
		// post type label
		$label = cpac_get_post_type_label($post_type);
				
		// id
		$id = cpac_sanitize_string($post_type); 
		
		// build draggable boxes
		$boxes = cpac_get_column_options($post_type);
		
		// class
		$class = cpac_menu_type_is_current($post_type) ? ' current' : ' hidden';
		
		$rows .= "
			<tr id='cpac-box-{$id}' valign='top' class='cpac-box-row{$class}'>
				<th class='cpac_post_type' scope='row'>
					{$label}
				</th>
				<td>
					<h3 class='cpac_post_type hidden'>{$label}</h3>
					{$boxes}
				</td>
			</tr>
		";
	}
	
	// Post Type Menu
	$menu = cpac_get_post_type_menu();
	
?>
	<div id="cpac" class="wrap">
		<h2>Advanced Admin Columns</h2>
		<?php echo $menu ?>
		<div class="postbox-container" style="width:70%;">
			<div class="metabox-holder">	
				<div class="meta-box-sortables">
				
					<div id="general-cpac-settings" class="postbox">
						<div title="Click to toggle" class="handlediv"><br></div>
						<h3 class="hndle">
							<span><?php _e('Admin Columns') ?></span>
						</h3>
						<div class="inside">
							<form method="post" action="options.php">
							
							<?php settings_fields( 'cpac-settings-group' ); ?>
							
							<table class="form-table">
							
								<?php echo $rows ?>								
								
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
							<span><?php _e('Restore defaults') ?></span>
						</h3>
						<div class="inside">
							<form method="post" action="">					
								<input type="submit" class="button" name="cpac-restore-defaults" value="<?php _e('Restore default settings') ?>" onclick="return confirm('<?php _e("Warning! ALL saved admin columns data will be deleted. This cannot be undone. \'OK\' to delete, \'Cancel\' to stop", "cpac"); ?>');" />
							</form>
							<p class="description"><?php _e('This will delete all column settings and restore the default settings.'); ?></p>
						</div>
					</div><!-- restore-cpac-settings -->
				
				</div>
			</div>
		</div><!-- .postbox-container -->
		
		<div class="postbox-container" style="width:20%;">
			<div class="metabox-holder">	
				<div class="meta-box-sortables">
				
					<div id="side-cpac-settings" class="postbox">
						<div title="Click to toggle" class="handlediv"><br></div>
						<h3 class="hndle">
							<span><?php _e('Need support?') ?></span>
						</h3>
						<div class="inside">
							<p><?php printf(__('If you are having problems with this plugin, please talk about them in the <a href="%s">Support forums</a>.', 'cpac'), 'http://wordpress.org' );?></p>
							<p><?php _e("If you're sure you've found a bug, or have a feature request, please submit it in the bug tracker.");?></p>
						</div>
					</div><!-- side-cpac-settings -->
					
					<div id="donation-cpac-settings" class="postbox hidden">
						<div title="Click to toggle" class="handlediv"><br></div>
						<h3 class="hndle">
							<span><?php _e('Donate $5, $10 or $20!') ?></span>
						</h3>
						<div class="inside">
							<p>
								<?php _e('Please donate a token of your appreciation if you like this plugin.') ?>
							</p>
							<p>
								<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
								<input type="hidden" name="cmd" value="_s-xclick">
								<input type="hidden" name="hosted_button_id" value="RUCY5GX4FNYFC">
								<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
								<img alt="" border="0" src="https://www.paypalobjects.com/nl_NL/i/scr/pixel.gif" width="1" height="1">
								</form>
							</p>
						</div>
					</div><!-- side-cpac-settings -->
				
				</div>
			</div>
		</div><!-- .postbox-container -->
		
	</div>
<?php
}

/**
 * Get a list of Column options per post type
 *
 * @access    private
 * @since     0.1
 */
function cpac_get_column_options($post_type) 
{	
	//get saved database columns
	$db_columns 		= cpac_get_db_columns($post_type);
	
	// get wp default columns
	$wp_default_columns = cpac_get_wp_default_columns($post_type);
	
	// get custom columns
	$wp_custom_columns 	= cpac_get_custom_columns($post_type);
	
	// merge default columns
	$default_columns = wp_parse_args($wp_custom_columns, $wp_default_columns);
		
	// define
	$list = '';
	
	// loop throught the active columns
	if ( $db_columns ) {
		foreach ( $db_columns as $key => $values ) {			
			
			// add items to the list
			$list .= cpac_get_box($post_type, $key, $values);
			
			unset($default_columns[$key]);			
		}
	}
	
	// loop through the inactive default columns
	if ($default_columns) {
		foreach ($default_columns as $key => $values ) {			
			
			// add items to the list
			$list .= cpac_get_box($post_type, $key, $values);			
		}
	}
	
	return "<ul class='cpac-option-list'>{$list}</ul>";
}

/**
 * Get checkbox
 *
 * @access    private
 * @since     0.1
 */
function cpac_get_box($post_type, $key, $values) 
{		
	// set state
	$state 	= isset($values['state']) ? $values['state'] : '';
	
	// set sortorder
	$sortorder 	= isset($values['sortorder']) && $values['sortorder'] == 'on' ? 'on' : '';
	
	// set type_label
	$type_label = isset($values['type_label']) && $values['type_label'] ? $values['type_label'] : '' ;
	
	// set label		
	$label = $values['label'];	
	
	// class
	$class = $state ? 'active': '';
	
	// more box options
	$more_options 	= cpac_get_additional_box_options($post_type, $key, $values);
	
	$list = "
		<li class='{$class}'>
			<div class='cpac-sort-handle'></div>			
			<div class='cpac-type-options'>
				
				<div class='cpac-checkbox'></div>
				<input type='hidden' class='cpac-state' name='cpac_options[columns][{$post_type}][{$key}][state]' value='{$state}'/>
				
				<input type='hidden' name='cpac_options[columns][{$post_type}][{$key}][sortorder]' value='{$sortorder}'/>				
				<input type='hidden' name='cpac_options[columns][{$post_type}][{$key}][type_label]' value='{$type_label}'/>
				<label class='main-label'>{$label}</label>								
			</div>
			<div class='cpac-meta-title'>
				<span>{$type_label}</span>					
				<a class='cpac-action' href='#open'>open</a>
			</div>
			<div class='cpac-type-inside'>				
				<label for='cpac_options[columns][{$post_type}][{$key}][label]'>Label: </label>
				<input type='text' name='cpac_options[columns][{$post_type}][{$key}][label]' value='{$label}' class='text'/>
				{$more_options}
			</div>
		</li>
	";
	
	return $list;
}

/**
 * Get additional box option fields
 *
 * @access    private
 * @since     0.1
 */
function cpac_get_additional_box_options($post_type, $key, $values) 
{
	$fields = '';
	
	// Custom Fields	
	if ( strpos($key, 'column-meta-') !== false )
		$fields .= cpac_get_box_options_customfields($post_type, $key, $values);
	
	return $fields;
}

/**
 * Box Options: Custom Fields
 *
 * @access    private
 * @since     0.1
 */
function cpac_get_box_options_customfields($post_type, $key, $values) 
{
	// get post meta fields	
	$fields = cpac_get_postmeta_by_posttype($post_type);
	
	if ( empty($fields) ) 
		return false;
	
	// current
	$current = isset($values['field']) && $values['field'] ? $values['field'] : '' ;
	
	// set options
	$options = '';
	foreach ($fields as $field) {	
		$options .= sprintf
		(
			'<option value="%s"%s>%s</option>',
			$field,
			$field == $current? ' selected="selected"':'',
			$field
		);		
    }
	
	if ( empty($options) )
		return false;
	
	$select = "
		<label for='cpac_options[columns][{$post_type}][{$key}][field]'>Custom Field: </label>
		<select name='cpac_options[columns][{$post_type}][{$key}][field]'>{$options}</select>
	";
	
	return $select;
}

/**
 * Get post meta fields by post type
 *
 * @access    private
 * @since     0.1
 */
function cpac_get_postmeta_by_posttype($post_type) 
{
	global $wpdb;
	// get mata fields
	$sql 	= 'SELECT DISTINCT meta_key	FROM '.$wpdb->postmeta.' pm	JOIN '.$wpdb->posts.' p	ON pm.post_id = p.ID WHERE p.post_type = "' . mysql_real_escape_string($post_type) . '" ORDER BY 1';
	$fields = $wpdb->get_results($sql, ARRAY_N);	
	
	// postmeta	
	if ( $fields ) {
		$meta_fields = array();
		foreach ($fields as $field) {
			// filter out hidden meta fields
			if (substr($field[0],0,1) != "_") {
				$meta_fields[] = $field[0];
			}
		}
		
		return $meta_fields;
	}	
	
	return false;
}

/**
 * Register admin scripts
 *
 * @access    private
 * @since     0.1
 */
function cpac_admin_enqueue_scripts() 
{
	wp_enqueue_script( 'dashboard' );
	wp_enqueue_script( 'jquery-ui-sortable' );
	wp_enqueue_script( 'cpac', CPAC_URL . '/assets/js/admin-column.js', array('jquery', 'jquery-ui-sortable'), '1.0' );	
}
add_action( 'admin_enqueue_scripts', 'cpac_admin_enqueue_scripts' );

/**
 * Get post types
 *
 * @access    private
 * @since     0.1
 */
function cpac_get_post_types()
{
	$post_types = get_post_types(array(
		'_builtin' => false
	));
	$post_types['post'] = 'post';
	$post_types['page'] = 'page';
	
	return $post_types;
}

/**
 * Register admin css
 *
 * @access    private
 * @since     0.1
 */
function cpac_admin_styles()
{
	wp_enqueue_style( 'cpac', CPAC_URL . '/assets/css/admin-column.css' );
}

/**
 * Register plugin options
 *
 * @access    private
 * @since     0.1
 */
function cpac_register_settings() 
{
	// If we have no options in the database, let's add them now.
	if ( false === get_option('cpac_options') )
		add_option( 'cpac_options', cpac_get_default_plugin_options() );
	
	register_setting( 'cpac-settings-group', 'cpac_options', 'cpac_options_callback' );
}
add_action( 'admin_init', 'cpac_register_settings' );

/**
 * Returns the default plugin options.
 *
 * @access    private
 * @since     0.1
 */
function cpac_get_default_plugin_options() 
{
	$default_plugin_options = array(		
		'post'	=> '',
		'page'	=> ''
	);
	return apply_filters( 'cpac_default_plugin_options', $default_plugin_options );
}

/**
 * Save geocode coordinates of focus location.
 *
 * @access    private
 * @since     0.1
 */
function cpac_options_callback($options)
{	
	return $options;
}

/**
 * Handle requests.
 *
 * @access    private
 * @since     0.1
 */
function cpac_handle_requests() 
{	
	// settings updated
	if ( isset($_REQUEST['settings-updated']) && $_REQUEST['settings-updated'] ) {
		$wp_default_columns = array();
		foreach ( cpac_get_post_types() as $post_type ) {
			$wp_default_columns[$post_type] = cpac_get_wp_default_columns($post_type);
		}
		update_option( 'cpac_options_default', $wp_default_columns );
	}		
	
	// restore defaults 
	if ( isset($_REQUEST['cpac-restore-defaults']) && $_REQUEST['cpac-restore-defaults'] ) {
		cpac_restore_defaults();
	}	
}

/**
 * Restore defaults
 *
 * @access    private
 * @since     0.1
 */
function cpac_restore_defaults() 
{	
	delete_option( 'cpac_options' );
	delete_option( 'cpac_options_default' );
}

/**
 * Returns excerpt
 *
 * @access    private
 * @since     0.1
 */
function cpac_get_the_excerpt($post_id, $charlength = 100) 
{
	global $post;  
	$save_post 	= $post;
	$post 		= get_post($post_id);
	$excerpt 	= get_the_excerpt();
	$post 		= $save_post;
	
	$output = '';
	if ( strlen($excerpt) > $charlength ) {
		$subex 		= substr($excerpt,0,$charlength-5);
		$exwords 	= explode(" ",$subex);
		$excut 		= -(strlen($exwords[count($exwords)-1]));
		if ( $excut < 0 ) {
			$output .= substr($subex,0,$excut);
		} else {
			$output .= $subex;
		}
		$output .= "[...]";
	} else {
		$output .= $excerpt;
	}
	
	return $output;
}

/**
 * Returns excerpt by charlength
 *
 * @access    private
 * @since     0.1
 */
function cpac_the_excerpt_max_charlength($charlength) {
	$excerpt = get_the_excerpt();
	$charlength++;
	if(strlen($excerpt)>$charlength) {
		$subex = substr($excerpt,0,$charlength-5);
		$exwords = explode(" ",$subex);
		$excut = -(strlen($exwords[count($exwords)-1]));
		if($excut<0) {
			echo substr($subex,0,$excut);
		} else {
			echo $subex;
		}
		echo "[...]";
	} else {
		echo $excerpt;
	}
}

/**
 * Manage custom column for Post Types.
 *
 * @access    private
 * @since     0.1
 */
function cpac_manage_column_value($key, $post_id) 
{
	$type = $key;
	
	// Check for taxonomies, such as column-taxonomy-[taxname]	
	if ( strpos($type, 'column-taxonomy-') !== false )
		$type = 'column-taxonomy';
	
	// Check for custom fields, such as column-meta-[customfieldname]
	if ( strpos($type, 'column-meta-') !== false )
		$type = 'column-meta';
	
	// Hook 
	do_action('cpac-manage-column', $type, $key, $post_id);
	
	// Switch Types
	$result = '';
	switch ($type) :			
		
		// Post ID
		case "column-postid" :
			$result = $post_id;
			break;
		
		// Excerpt
		case "column-excerpt" :
			$result = cpac_get_the_excerpt($post_id);
			break;
		
		// Featured Image
		case "column-featured_image" :
			$result = get_the_post_thumbnail($post_id, array(80,80));			
			break;
			
		// Sticky Post
		case "column-sticky" :
			if ( is_sticky($post_id) )			
				$result = '<img alt="sticky" src="' . CPAC_URL . 'assets/images/checkmark.png" />';
			break;
		
		// Order
		case "column-order" :
			$result = get_post_field('menu_order', $post_id);			
			break;
		
		// Page template
		case "column-page-template" :
			// file name
			$page_template 	= get_post_meta($post_id, '_wp_page_template', true);			
			// all page templates
			$templates 		= get_page_templates();
			// template name
			$result = array_search($page_template, $templates);			
			break;
		
		// Slug
		case "column-page-slug" :
			$result = get_post($post_id)->post_name;			
			break;
		
		// Taxonomy
		case "column-taxonomy" :
			$tax 	= str_replace('column-taxonomy-','',$key);
			$tags 	= get_the_terms($post_id, $tax);
			$tarr 	= array();
			if ( !empty($tags) ) {
				foreach($tags as $tag) {				
					$tarr[] = $tag->name;	
				}
				$result = implode(', ', $tarr);
			}			
			break;
		
		// Custom Field
		case "column-meta" :
			$columns = cpac_get_db_columns( get_post_type($post_id) );
			$field	 = isset($columns[$key]['field']) ? $columns[$key]['field'] : '';
			$meta 	 = get_post_meta($post_id, $field);
			// multiple meta values
			if ( is_array($meta) ) {
				$array = array();
				foreach ( $meta as $m ) {
					if ( is_string($m) && $m )
						$array[] = $m;
				}
				echo implode(', ',$array);
			}
			// single meta value
			else {
				echo $meta;
			}
			
			break;
		
		default :
			$result = get_post_meta( $post_id, $key, true );
					
	endswitch;
	
	if ( empty($result) )
		echo '&nbsp;';
	
	echo $result;	
}	
add_action( 'manage_pages_custom_column', 'cpac_manage_column_value', 10, 2 );	
add_action( 'manage_posts_custom_column', 'cpac_manage_column_value', 10, 2 );	
 
/**
 *	Set Columns for Registering
 *
 * 	@access    private
 * 	@since     0.1
 */
function cpac_set_column($columns, $post_type) 
{
	$db_columns	= cpac_get_db_columns($post_type);
	
	if ( !$db_columns )
		return $columns;

	// set already loaded columns by plugins
	$set_columns = cpac_filter_preset_columns($columns, $post_type);
			
	// loop through columns
	foreach ( $db_columns as $key => $values ) {
		
		// is active
		if ( isset($values['state']) && $values['state'] == 'on' ){				
			
			// register format
			$set_columns[$key] = $values['label'];				
		}
	}
	return $set_columns;		
}

/**
 *	Set columns. These columns apply either for every post or set by a plugin.
 * 	@access    private
 * 	@since     0.1
 */
function cpac_filter_preset_columns($columns, $post_type = 'post') 
{
	$options 	= get_option('cpac_options_default');
	
	if ( !$options )
		return $columns;
	
	// we use the wp default columns for filtering...
	$db_columns 	= $options[$post_type];		
	
	// ... the ones that are set by plugins, theme functions and such.
	$dif_columns 	= array_diff(array_keys($columns), array_keys($db_columns));
	
	// we add those to the columns
	$pre_columns = array();
	if ( $dif_columns ) {
		foreach ( $dif_columns as $column ) {
			$pre_columns[$column] = $columns[$column];
		}
	}
	
	return $pre_columns;
}

/**
 *	Set sortable columns
 *
 * 	@access    private
 * 	@since     0.1
 */
function cpac_set_sortable_filter($columns, $post_type) 
{
	$db_columns	= cpac_get_db_columns($post_type);
		
	if ( !$db_columns )
		return $columns;
	
	// loop through columns
	foreach ( $db_columns as $key => $values ) {
		
		// is active
		if ( isset($values['sortorder']) && $values['sortorder'] == 'on' ){				
			
			// register format
			$columns[$key] = cpac_sanitize_string($values['label']);			
		}
	}	
	return $columns;
}

/**
 *	Register Columns
 *
 * 	@access    private
 * 	@since     0.1
 */
function cpac_register_columns() 
{	
	foreach ( cpac_get_post_types() as $post_type ) {
		
		// register column per post type... and we trigger an anonymous callback function
		add_filter("manage_edit-{$post_type}_columns", function($columns) {
			global $post_type;
			
			// set activated columns
			$columns = cpac_set_column($columns, $post_type);
			
			return $columns;			
		});		
		
		// register column as sortable
		add_filter( "manage_edit-{$post_type}_sortable_columns", function($columns) {
			global $post_type;
			
			// set sortable columns
			$columns = cpac_set_sortable_filter($columns, $post_type);
						
			return $columns;
		});
	}
}
add_action('admin_init','cpac_register_columns');

/**
 * 	Get WP default supported admin columns per post type.
 *
 * 	@access    private
 * 	@since     0.1
 */
function cpac_get_wp_default_columns($post_type = 'post') 
{
	// load some dependencies
	require_once(ABSPATH . 'wp-admin\includes\template.php');
	require_once(ABSPATH . 'wp-admin\includes\class-wp-list-table.php');
	require_once(ABSPATH . 'wp-admin\includes\class-wp-posts-list-table.php');
	
	// we need to change the current screen
	global $current_screen;
	$org_current_screen = $current_screen;
	
	// overwrite current_screen global with our post type of choose...
	$current_screen->post_type = $post_type;
	
	// ...so we can get its columns
	$columns = WP_Posts_List_Table::get_columns();
	
	// we remove the checkbox column as an option... 
	unset($columns['cb']);
	
	// change to uniform format
	$uniform_columns = array();
	foreach ( $columns as $key => $label ) {
		// comment label
		$type_label = strpos( $label, 'comment-grey-bubble.png') ? 'Comments' : $label;
		
		$uniform_colums[$key] = array(
			'label'			=> $label,
			'state'			=> 'on',
			'filter'		=> '',
			'type_label' 	=> $type_label
		);
	}
	
	// reset current screen
	$current_screen = $org_current_screen;
	
	return $uniform_colums;
}

/**
 * Add extra columns
 *
 * @access    private
 * @since     0.1
 */
function cpac_get_custom_columns($post_type) 
{
	$custom_columns = array();
	
	// deafult arguments
	$defaults = array(		
		'label'			=> '',
		'type_label' 	=> 'Custom',
		'sortorder'		=> '',
		'filter' 		=> '',
		'state' 		=> '',
	);	
	
	// Thumbnail support
	if ( post_type_supports($post_type, 'thumbnail') ) {
		$custom_columns['column-featured_image'] = wp_parse_args( array(
			'label'			=> 'Featured Image',
			'type_label' 	=> 'Image',
		), $defaults);
	}
	
	// Excerpt support
	if ( post_type_supports($post_type, 'editor') ) {
		$custom_columns['column-excerpt'] = wp_parse_args( array(
			'label'			=> 'Excerpt',
			'type_label' 	=> 'Excerpt',
		), $defaults);
	}
	
	// Sticky support
	if ( $post_type == 'post' ) {		
		$custom_columns['column-sticky'] = wp_parse_args( array(
			'label'			=> 'Sticky',
			'type_label' 	=> 'Sticky',
		), $defaults);
	}
	
	// Order support
	if ( post_type_supports($post_type, 'page-attributes') ) {
		$custom_columns['column-order'] = wp_parse_args( array(
			'label'			=> 'Page Order',
			'type_label' 	=> 'Order',
			'sortorder'		=> 'on',
		), $defaults);
	}
	
	// Page Template
	if ( $post_type == 'page' ) { 
		$custom_columns['column-page-template'] = wp_parse_args( array(
			'label'			=> 'Page Template',
			'type_label' 	=> 'Template',
			'sortorder'		=> 'on',
		), $defaults);	
	}
	
	// Taxonomy support
	$taxonomies = get_object_taxonomies($post_type, 'objects');
	if ( $taxonomies ) {
		foreach ( $taxonomies as $tax_slug => $tax ) {
			// exclude wp default taxonomies
			if ( $tax_slug != 'post_tag' && $tax_slug != 'category' ) {
				$custom_columns['column-taxonomy-'.$tax->name] = wp_parse_args( array(
					'label'			=> $tax->label,
					'type_label'	=> 'Taxonomy',
				), $defaults);
			}
		}
	}
	
	// Custom Field support
	if ( cpac_get_postmeta_by_posttype($post_type) ) {
		$custom_columns['column-meta-1'] = wp_parse_args( array(
			'label'			=> 'Custom Field #1',
			'type_label'	=> 'Field',
			'field'			=> ''
		), $defaults);
		$custom_columns['column-meta-2'] = wp_parse_args( array(
			'label'			=> 'Custom Field #2',
			'type_label'	=> 'Field',
			'field'			=> ''
		), $defaults);
	}
	
	// Post ID support
	$custom_columns['column-postid'] = wp_parse_args( array(
		'label'			=> 'ID',
		'type_label' 	=> 'ID',
		'sortorder'		=> 'on',
	), $defaults);
	
	// Slug support
	$custom_columns['column-page-slug'] = wp_parse_args( array(
		'label'			=> 'Slug',
		'type_label' 	=> 'Slug',
		'sortorder'		=> 'on',
	), $defaults);		
	
	return apply_filters('cpac-custom-columns', $custom_columns);
}

/**
 * Admin requests for orderby column
 *
 * @access    private
 * @since     0.1
 */
function cpac_get_db_columns($post_type)
{ 
	// get plugin options
	$options 		= (array) get_option('cpac_options');
	
	// get saved columns
	if ( isset($options['columns'][$post_type]) )
		return $options['columns'][$post_type];
	
	return false;
}

/**
 * Post Type Menu
 *
 * @access    private
 * @since     0.1
 */
function cpac_get_post_type_menu() 
{
	// set
	$menu 	= '';
	$count 	= 1;
	
	// referer
	$referer = '';
	if ( isset($_REQUEST['cpac_type']) && $_REQUEST['cpac_type'] )
		$referer = $_REQUEST['cpac_type'];
		
	// loop
	foreach ( cpac_get_post_types() as $post_type ) {
		$label 		 = cpac_get_post_type_label($post_type);
		$clean_label = cpac_sanitize_string($post_type);
		
		// divider
		$divider 	= $count++ == 1 ? '' : ' | ';
		
		// current		
		$current = '';
		if ( cpac_menu_type_is_current($post_type) )
			$current = ' class="current"';
		
		// menu list
		$menu .= "
			<li>{$divider}<a{$current} href='#cpac-box-{$clean_label}'>{$label}</a></li>
		";
	}

	return "
	<div class='cpac-menu'>
		<ul class='subsubsub'>
			{$menu}
		</ul>
	</div>
	";
}

/**
 * Get Current Box Type
 *
 * @access    private
 * @since     0.1
 */
function cpac_menu_type_is_current( $post_type ) 
{
	// referer
	$referer = '';
	if ( isset($_REQUEST['cpac_type']) && $_REQUEST['cpac_type'] )
		$referer = $_REQUEST['cpac_type'];
	
	// get label
	$label 		 = cpac_get_post_type_label($post_type);
	$clean_label = cpac_sanitize_string($post_type);
	
	// first element in post type array
	$first 		= current(cpac_get_post_types());
	
	// current
	$count = 1;
	$current = '';
	if ( $referer ) {
		if ( $referer == 'cpac-box-'.$clean_label ) {
			return true;
		}			
	} elseif ( $first == $post_type  ) {
		return true;
	}	
	return false;	
}

/**
 * Get Post Type Label
 *
 * @access    private
 * @since     0.1
 */
function cpac_get_post_type_label( $post_type ) 
{
	$posttype_obj 	= get_post_type_object($post_type);
	$label 			= $posttype_obj->labels->singular_name;
	return $label;
}

/**
 * Admin requests for orderby column
 *
 * @access    private
 * @since     0.1
 */
function cpac_requests_orderby_column( $vars ) 
{
	if ( isset( $vars['orderby'] ) ) {	
		// get saved columns
		$db_columns = cpac_get_db_columns($vars['post_type']);
		
		// sanitizing label
		$label 		= cpac_sanitize_string($db_columns['column-order']['label']);
		
		// Check for Page Order
		if ( $vars['orderby'] == $label ) {
			$vars['orderby'] = 'menu_order';
		}
	} 
	return $vars;
}
add_filter( 'request', 'cpac_requests_orderby_column' );

/**
 * Sanitize label
 * Uses intern wordpress function esc_url so it matches the label sorting url.
 *
 * @access    private
 * @since     0.1
 */
function cpac_sanitize_string($string) 
{	
	$string = esc_url($string);
	return str_replace('http://','', $string);
}

?>