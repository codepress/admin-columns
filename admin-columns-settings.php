<?php

/* 
To do:
x sorting
- add filters
- ajax adding of columns 
- OOP

class setColumns
class adminColumns

*/

update_option( 'cpac_options', '');

/**
 * Admin Menu.
 *
 * Create the admin menu link for the settings page.
 *
 * @access    private
 * @since     0.3
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
	// build form rows
	$rows = '';	
	
	// loop through post types
	foreach ( cpac_get_post_types() as $post_type ) {
		
		// post type label
		$posttype_obj 	= get_post_type_object($post_type);
		$label 			= $posttype_obj->labels->singular_name;
		
		// build options
		$checkboxes = cpac_get_column_options($post_type);
		
		$rows .= "
			<tr valign='top'>
				<th class='cpac_post_type' scope='row'>
					{$label}
				</th>
				<td>
					{$checkboxes}
				</td>
			</tr>
		";
	}
	
?>
	<div class="wrap">
		<h2>Advanced Admin Columns</h2>
		<div class="postbox-container" style="width:100%;">
			<div class="metabox-holder">	
				<div class="meta-box-sortables">		
					<div id="general-cpac-settings" class="postbox">
						<h3 class="hndle">
							<span>Columns</span>
						</h3>
						<div class="inside">
							<form method="post" action="options.php">
							
							<?php settings_fields( 'cpac-settings-group' ); ?>
							
							<table class="form-table">				
								
								<?php echo $rows ?>
								
								<tr valign="top">
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
				
				</div>
			</div>
		</div>
	</div>
<?php
}

/**
 * Get a list of Column options per post type
 *
 * @access    private
 * @since     0.3
 */
function cpac_get_column_options($post_type) 
{	
	// get plugin options
	$options 		= (array) get_option('cpac_options');
	
	// get saved columns
	$saved_columns = '';
	if ( isset($options['columns'][$post_type]) )
		$saved_columns 	= $options['columns'][$post_type];	
	
	// get wp default columns
	$wp_default_columns = cpac_get_wp_default_columns($post_type);
	
	// get extra columns
	$wp_extra_columns 	= cpac_get_extra_columns($post_type);
	
	// merge default columns
	$default_columns = wp_parse_args($wp_extra_columns, $wp_default_columns);
		
	// define
	$list = '';
	
	// loop throught the active columns
	if ( $saved_columns ) {
		foreach ( $saved_columns as $key => $values ) {			
			
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
 * @since     0.3
 */
function cpac_get_box($post_type, $key, $values) 
{	
	// set checked state
	$checked = isset($values['state']) && $values['state'] == 'on' ? " checked='yes'" : '';
	
	// set sortable
	$sortable = isset($values['sortable']) && $values['sortable'] == 'on' ? 'on' : '';
	
	// set description
	$description = isset($values['description']) && $values['description'] ? $values['description'] : '' ;
	
	// label		
	$label = $values['label'];
	
	$list = "
		<li>
			<div class='cpac-sort-handle'></div>
			<div class='cpac-type-options'>
				<input id='cpac-{$post_type}-{$key}' name='cpac_options[columns][{$post_type}][{$key}][state]' type='checkbox'{$checked}>
				<input type='hidden' name='cpac_options[columns][{$post_type}][{$key}][label]' value='{$label}'/>
				<input type='hidden' name='cpac_options[columns][{$post_type}][{$key}][sortable]' value='{$sortable}'/>
				<input type='hidden' name='cpac_options[columns][{$post_type}][{$key}][description]' value='{$description}'/>
				<label for='cpac-{$post_type}-{$key}'>{$label}</label>
				<div class='cpac-meta-title'>{$description}</div>
			</div>
			<div class='cpac-type-inside' style='display:none'>
			</div>
		</li>
	";
	
	return $list;
}

/**
 * Register admin scripts
 *
 * @access    private
 * @since     0.3
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
 * @since     0.3
 */
function cpac_get_post_types()
{
	$post_types = get_post_types(array(
		'_builtin' => false
	));
	$post_types[] = 'post';
	$post_types[] = 'page';
	
	return $post_types;
}

/**
 * Register admin css
 *
 * @access    private
 * @since     0.3
 */
function cpac_admin_styles()
{
	wp_enqueue_style( 'cpac', CPAC_URL . '/assets/css/admin-column.css' );
}

/**
 * Register plugin options
 *
 * @access    private
 * @since     0.3
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
 * @since     0.3
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
 * @since     0.3
 */
function cpac_options_callback($options)
{	
	return $options;
}

/**
 * Handle requests.
 *
 * @access    private
 * @since     0.3
 */
function cpac_take_action() 
{		
	// update User Info
	/* if ( isset($_REQUEST['update-coordinates']) && $_REQUEST['update-coordinates'] ) {
		cpac_update_users();
	} */	
	
}

/**
 * Returns excerpt
 *
 * @access    private
 * @since     0.3
 */
function cpac_get_the_excerpt($post_id) {
	global $post;  
	$save_post 	= $post;
	$post 		= get_post($post_id);
	$output 	= get_the_excerpt();
	$post 		= $save_post;
	return $output;
}

/**
 * Custom Columns for Post Types.
 *
 * @access    private
 * @since     0.3
 */
function cpac_add_column_value($type, $post_id) 
{
	// save type as value
	$value = $type;
	
	// check for taxonomy
	if ( strpos($type, 'column-taxonomy') !== false )
		$type = 'taxonomy'; 
	
	switch ($type) :			
		
		// Excerpt
		case "column-excerpt" :
			echo cpac_get_the_excerpt($post_id);
			break;
		
		// Featured Image
		case "column-featured_image" :
			echo get_the_post_thumbnail($post_id, array(80,80));			
			break;
			
		// Sticky Post
		case "column-sticky" :
			if ( is_sticky($post_id) )			
				echo '<img alt="sticky" src="' . CPAC_URL . 'assets/images/checkmark.png" />';
			break;
		
		// Order
		case "column-order" :
			echo get_post_field('menu_order', $post_id);			
			break;
		
		// Page template
		case "column-page-template" :
			// file name
			$page_template 	= get_post_meta($post_id, '_wp_page_template', true);			
			// all page templates
			$templates 		= get_page_templates();
			// template name
			echo array_search($page_template, $templates);			
			break;
		
		// Slug
		case "column-page-slug" :
			$p = get_post($post_id);
			echo $p->post_name;
			break;
		
		// Taxonomy
		case "taxonomy" :
			$tax 	= str_replace('column-taxonomy-','',$value);
			$tags 	= get_the_terms($post_id, $tax);
			$tarr 	= array();
			if ( !empty($tags) ) {
				foreach($tags as $tag) {				
					$tarr[] = $tag->name;	
				}
				echo implode(', ', $tarr);
			}			
			break;
		
		default :
			$value = get_post_meta( $post_id, $value, true );
			echo $value;
		
	endswitch;
}	
add_action( 'manage_pages_custom_column', 'cpac_add_column_value', 10, 2 );	
add_action( 'manage_posts_custom_column', 'cpac_add_column_value', 10, 2 );	

/**
 *	Set Columns for Registering
 */
function cpac_set_column($columns, $post_type) 
{
	$options = get_option('cpac_options');	
	
	if ( !$options )
		return $columns;
	
	if ( isset($options['columns'][$post_type]) ) {
		
		$selected_columns   = $options['columns'][$post_type];			
		if ( !empty($selected_columns)) {

			// add the default checkbox
			$set_columns['cb'] = $columns['cb'];
			
			// loop through columns
			foreach ( $selected_columns as $key => $values ) {
				
				// is active
				if ( isset($values['state']) && $values['state'] == 'on' ){				
					
					// register format
					$set_columns[$key] = $values['label'];				
				}
			}
			return $set_columns;
		}
	}
	
	return $columns;
}

/**
 *	Set sortable columns
 */
function cpac_set_sortable($columns, $post_type) 
{
	$options = get_option('cpac_options');	
		
	if ( !$options )
		return $columns;
	
	if ( isset($options['columns'][$post_type]) ) {
		
		$selected_columns = $options['columns'][$post_type];			
		if ( !empty($selected_columns)) {
			
			// loop through columns
			foreach ( $selected_columns as $key => $values ) {
				
				// is active
				if ( isset($values['sortable']) && $values['sortable'] == 'on' ){				
					
					// register format
					$columns[$key] = $values['label'];				
				}
			}
		}
	}
	
	return $columns;
}

/**
 *	Register Columns
 */
function cpac_register_columns() 
{	
	foreach ( cpac_get_post_types() as $post_type ) {
		
		// register column per post type... and we trigger an anonymous callback function
		add_filter("manage_edit-{$post_type}_columns", function($columns) {
			
			// we need the post type
			global $post_type;
			
			// set activated columns
			$columns = cpac_set_column($columns, $post_type);
			
			return $columns;			
		}); 
		
		
		// register column as sortable
		add_filter( "manage_edit-{$post_type}_sortable_columns", function($columns) {
			global $post_type;
			
			// set sortable columns
			$columns = cpac_set_sortable($columns, $post_type);
						
			return $columns;
		}); 
		
		
	}
}
add_action('admin_init','cpac_register_columns');

/**
 * Get WP default supported admin columns per post type.
 *
 * @access    private
 * @since     0.3
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
		$uniform_colums[$key]['label'] = $label;
		$uniform_colums[$key]['state'] = 'on';
	}
	
	// reset current screen
	$current_screen = $org_current_screen;
	
	return $uniform_colums;
}

/**
 * Add extra columns
 *
 * @access    private
 * @since     0.3
 */
function cpac_get_extra_columns($post_type) 
{
	$extra_columns = array();
	
	// Thumbnail support
	if ( post_type_supports($post_type, 'thumbnail') ) {
		$extra_columns['column-featured_image'] = array(
			'state' => '',
			'label'	=> __('Featured Image'),
			'description' => 'Custom'
		);
	}
	
	// Excerpt support
	if ( post_type_supports($post_type, 'editor') ) {
		$extra_columns['column-excerpt'] = array(
			'state' 	=> '',
			'label'		=> __('Excerpt'),
			'sortable'	=> 'on',
			'description' => 'Custom'
		);
	}
	
	// Sticky support
	if ( $post_type == 'post' ) {		
		$extra_columns['column-sticky'] = array(
			'state' 	=> '',
			'label'		=> __('Sticky'),
			'sortable'	=> 'on',
			'description' => 'Custom'			
		);
	}
	
	// Order support
	if ( post_type_supports($post_type, 'page-attributes') ) {
		$extra_columns['column-order'] = array(
			'state' 	=> '',
			'label'		=> __('Order'),
			'sortable'	=> 'on',
			'description' => 'Custom'
		);
	}
	
	// Page Template
	if ( $post_type == 'page' ) { 
		$extra_columns['column-page-template'] = array(
			'state' 		=> '',
			'label'			=> __('Template'),
			'description'	=> 'Custom'			
		);		
	}
	
	// Taxonomy support
	$taxonomies = get_object_taxonomies($post_type, 'objects');
	if ( $taxonomies ) {
		foreach ( $taxonomies as $tax ) {
			$extra_columns['column-taxonomy-'.$tax->name] = array(
				'state' 		=> '',
				'label'			=> $tax->label,
				'description'	=> 'Taxonomy'
			);			
		}
	}
	
	// Slug support
	$extra_columns['column-page-slug'] = array(
		'state' => '',
		'label'	=> 'Slug',
		'description' => 'Custom'		
	);		
	
	return $extra_columns;
}

/**
 * Add custom column through AJAX
 *
 * @access    private
 * @since     0.3
 */
function cpac_ajax_add_custom_column() 
{	
	// get query variable
	$key 		= isset($_POST['column_type']) ? $_POST['column_type'] : '';
	$post_type 	= isset($_POST['post_type']) ? $_POST['post_type'] : '';
	$label 		= isset($_POST['label']) ? $_POST['label'] : '';
	
	// set values
	$values = array(
		'state'	=> 'on',
		'label'	=> $label
	);
	
	$list = cpac_get_box($post_type, $key, $values);	

	echo json_encode($list);
	exit;
}
add_action('wp_ajax_nopriv_cpac_add_custom_column','cpac_ajax_add_custom_column', 10, 2);
add_action('wp_ajax_cpac_add_custom_column','cpac_ajax_add_custom_column', 10, 2);

?>