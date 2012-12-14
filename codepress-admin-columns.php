<?php
/*

Plugin Name: 		Codepress Admin Columns
Version: 			1.4.9
Description: 		Customize columns on the administration screens for post(types), pages, media, comments, links and users with an easy to use drag-and-drop interface.
Author: 			Codepress
Author URI: 		http://www.codepress.nl
Plugin URI: 		http://www.codepress.nl/plugins/codepress-admin-columns/
Text Domain: 		codepress-admin-columns
Domain Path: 		/languages
License:			GPLv2

Copyright 2011-2012  Codepress  info@codepress.nl

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

define( 'CPAC_VERSION', 	'1.4.9' );
define( 'CPAC_TEXTDOMAIN', 	'codepress-admin-columns' );
define( 'CPAC_SLUG', 		'codepress-admin-columns' );
define( 'CPAC_URL', 		plugins_url('', __FILE__) );

// only run plugin in the admin interface
if ( !is_admin() )
	return false;

/**
 * Dependencies
 *
 * @since     1.3
 */
require_once dirname( __FILE__ ) . '/classes/sortable.php';
require_once dirname( __FILE__ ) . '/classes/values.php';
require_once dirname( __FILE__ ) . '/classes/values/posts.php';
require_once dirname( __FILE__ ) . '/classes/values/users.php';
require_once dirname( __FILE__ ) . '/classes/values/media.php';
require_once dirname( __FILE__ ) . '/classes/values/link.php';
require_once dirname( __FILE__ ) . '/classes/values/comments.php';
require_once dirname( __FILE__ ) . '/classes/license.php';
require_once dirname( __FILE__ ) . '/classes/third_party.php';

/**
 * Codepress Admin Columns Class
 *
 * @since     1.0
 *
 */
class Codepress_Admin_Columns
{
	private $post_types,
			$codepress_url,
			$wordpress_url,
			$admin_page,
			$use_hidden_custom_fields;

	/**
	 * Constructor
	 *
	 * @since     1.0
	 */
	function __construct()
	{
		// wp is loaded
		add_action( 'wp_loaded', array( $this, 'init') );
	}

	/**
	 * Initialize plugin.
	 *
	 * Loading sequence is determined and intialized.
	 *
	 * @since     1.0
	 */
	public function init()
	{
		// vars
		$this->post_types 		= self::get_post_types();

		// set
		$this->codepress_url	= 'http://www.codepress.nl/plugins/codepress-admin-columns';
		$this->plugins_url		= 'http://wordpress.org/extend/plugins/codepress-admin-columns/';
		$this->wordpress_url	= 'http://wordpress.org/tags/codepress-admin-columns';

		// enable the use of custom hidden fields
		$this->use_hidden_custom_fields = apply_filters('cpac_use_hidden_custom_fields', false);

		// translations
		load_plugin_textdomain( CPAC_TEXTDOMAIN, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

		// register settings
		add_action( 'admin_menu', array( $this, 'settings_menu') );
		add_action( 'admin_init', array( $this, 'register_settings') );

		// styling & scripts
		add_action( 'admin_enqueue_scripts' , array( $this, 'column_styles') );
		add_filter( 'admin_body_class', array( $this, 'admin_class' ) );
		add_action( 'admin_head', array( $this, 'admin_css') );

		// register columns
		add_action( 'admin_init', array( $this, 'register_columns_headings' ) );
		add_action( 'admin_init', array( $this, 'register_columns_values' ) );

		// action ajax
		add_action( 'wp_ajax_cpac_addon_activation', array( $this, 'ajax_activation'));

		// handle requests gets a low priority so it will trigger when all other plugins have loaded their columns
		add_action( 'admin_init', array( $this, 'handle_requests' ), 1000 );

		// filters
		add_filter( 'plugin_action_links',  array( $this, 'add_settings_link'), 1, 2);
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
			// Page title
			esc_html__( 'Admin Columns Settings', CPAC_TEXTDOMAIN ),
			// Menu Title
			esc_html__( 'Admin Columns', CPAC_TEXTDOMAIN ),
			// Capability
			'manage_options',
			// Menu slug
			CPAC_SLUG,
			// Callback
			array( $this, 'plugin_settings_page')
		);

		// set admin page
		$this->admin_page = $page;

		// settings page specific styles and scripts
		add_action( "admin_print_styles-$page", array( $this, 'admin_styles') );
		add_action( "admin_print_scripts-$page", array( $this, 'admin_scripts') );

		// add help tabs
		add_action("load-$page", array( $this, 'help_tabs'));
	}

	/**
	 * Add Settings link to plugin page
	 *
	 * @since	1.0
	 * @param	$links string - all settings links
	 * @param	$file string - plugin filename
	 * @return	string - link to settings page
	 */
	function add_settings_link( $links, $file )
	{
		if ( $file != plugin_basename( __FILE__ ))
			return $links;

		array_unshift($links, '<a href="' . admin_url("admin.php") . '?page=' . CPAC_SLUG . '">' . __( 'Settings' ) . '</a>');
		return $links;
	}

	/**
	 *	Register Column Values
	 *
	 *	initializes each Class per type
	 *
	 * 	@since     1.0
	 */
	public function register_columns_values()
	{
		new CPAC_Posts_Values();
		new CPAC_Link_Values();
		new CPAC_Media_Values();
		new CPAC_Users_Values();
		new CPAC_Comments_Values();
	}
	/**
	 *	Register Columns Headings
	 *
	 *	apply_filters location in includes/screen.php
	 *
	 * 	@since     1.0
	 */
	public function register_columns_headings()
	{
		/** Posts */
	 	foreach ( $this->post_types as $post_type ) {

			// register column per post type
			add_filter("manage_edit-{$post_type}_columns", array($this, 'callback_add_posts_column_headings'));
		}

		/** Users */
		add_filter( "manage_users_columns", array($this, 'callback_add_users_column_headings'), 9);
		// give higher priority, so it will load just before other plugins to prevent conflicts

		/** Media */
		add_filter( "manage_upload_columns", array($this, 'callback_add_media_column_headings'));

		/** Links */
		add_filter( "manage_link-manager_columns", array($this, 'callback_add_links_column_headings'));

		/** Comments */
		add_filter( "manage_edit-comments_columns", array($this, 'callback_add_comments_column_headings'));
	}

	/**
	 *	Callback add Posts Column
	 *
	 * 	@since     1.0
	 */
	public function callback_add_posts_column_headings($columns)
	{
		return $this->add_columns_headings( get_post_type(), $columns);
	}

	/**
	 *	Callback add Users column
	 *
	 * 	@since     1.1
	 */
	public function callback_add_users_column_headings($columns)
	{
		return $this->add_columns_headings('wp-users', $columns);
	}

	/**
	 *	Callback add Media column
	 *
	 * 	@since     1.3
	 */
	public function callback_add_media_column_headings($columns)
	{
		return $this->add_columns_headings('wp-media', $columns);
	}

	/**
	 *	Callback add Links column
	 *
	 * 	@since     1.3.1
	 */
	public function callback_add_links_column_headings($columns)
	{
		return $this->add_columns_headings('wp-links', $columns);
	}

	/**
	 *	Callback add Comments column
	 *
	 * 	@since     1.3.1
	 */
	public function callback_add_comments_column_headings($columns)
	{
		return $this->add_columns_headings('wp-comments', $columns);
	}



	/**
	 *	Add managed columns by Type
	 *
	 * 	@since 1.4.6.5
	 */
	private function get_comment_icon()
	{
		return "<span class='vers'><img src='" . trailingslashit( get_admin_url() ) . 'images/comment-grey-bubble.png' . "' alt='Comments'></span>";
	}

	/**
	 *	Add managed columns by Type
	 *
	 * 	@since     1.1
	 */
	protected function add_columns_headings( $type, $columns )
	{
		// only get stored columns.. the rest we don't need
		$db_columns	= self::get_stored_columns($type);

		if ( !$db_columns )
			return $columns;

		// filter already loaded columns by plugins
		$set_columns = $this->filter_preset_columns( $type, $columns );

		// loop through columns
		foreach ( $db_columns as $id => $values ) {
			// is active
			if ( isset($values['state']) && $values['state'] == 'on' ){

				$label = $values['label'];

				// exception for comments
				if( 'comments' == $id ) {
					$label = $this->get_comment_icon();
				}

				// register format
				$set_columns[$id] = $label;
			}
		}

		return $set_columns;
	}

	/**
	 * Filter preset columns. These columns apply either for every post or set by a plugin.
	 *
	 * @since     1.0
	 */
	private function filter_preset_columns( $type, $columns )
	{
		$options 	= get_option('cpac_options_default');

		if ( !$options )
			return $columns;

		// we use the wp default columns for filtering...
		$stored_wp_default_columns 	= $options[$type];

		// ... the ones that are set by plugins, theme functions and such.
		$dif_columns 	= array_diff(array_keys($columns), array_keys($stored_wp_default_columns));

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
	 * Get a list of Column options per post type
	 *
	 * @since     1.0
	 */
	private function get_column_boxes($type)
	{
		// merge all columns
		$display_columns 	= $this->get_merged_columns($type);

		// define
		$list = '';

		// loop throught the active columns
		if ( $display_columns ) {
			foreach ( $display_columns as $id => $values ) {

				$classes = array();

				// set state
				$state 	= isset($values['state']) ? $values['state'] : '';

				// class
				$classes[] = "cpac-box-{$id}";
				if ( $state ) {
					$classes[] = 'active';
				}
				if ( ! empty($values['options']['class']) ) {
					$classes[] = $values['options']['class'];
				}
				$class = implode(' ', $classes);

				// more box options
				$more_options 	= $this->get_additional_box_options($type, $id, $values);
				$action 		= "<a class='cpac-action' href='#open'>open</a>";

				// type label
				$type_label = isset($values['options']['type_label']) ? $values['options']['type_label'] : '';

				// label
				$label = isset($values['label']) ? str_replace("'", '"', $values['label']) : '';

				// main label
				$main_label = $values['label'];

				// main label exception for comments
				if ( 'comments' == $id ) {
					$main_label = $this->get_comment_icon();
				}

				// width
				$width			= isset($values['width']) ? $values['width'] : 0;
				$width_descr	= isset($values['width']) && $values['width'] > 0 ? $values['width'] . '%' : __('default', CPAC_TEXTDOMAIN);

				// hide box options
				$label_hidden = '';
				if ( ! empty($values['options']['hide_options']) || strpos($label, '<img') !== false ) {
					$label_hidden = ' style="display:none"';
				}

				$list .= "
					<li class='{$class}'>
						<div class='cpac-sort-handle'></div>
						<div class='cpac-type-options'>
							<div class='cpac-checkbox'></div>
							<input type='hidden' class='cpac-state' name='cpac_options[columns][{$type}][{$id}][state]' value='{$state}'/>
							<label class='main-label'>{$main_label}</label>
						</div>
						<div class='cpac-meta-title'>
							{$action}
							<span>{$type_label}</span>
						</div>
						<div class='cpac-type-inside'>
							<label for='cpac_options-{$type}-{$id}-label'{$label_hidden}>Label: </label>
							<input type='text' name='cpac_options[columns][{$type}][{$id}][label]' id='cpac_options-{$type}-{$id}-label' value='{$label}' class='text'{$label_hidden}/>
							<label for='cpac_options-{$type}-{$id}-width'>" . __('Width', CPAC_TEXTDOMAIN) . ":</label>
							<input type='hidden' maxlength='4' class='input-width' name='cpac_options[columns][{$type}][{$id}][width]' id='cpac_options-{$type}-{$id}-width' value='{$width}' />
							<div class='description width-decription' title='" . __('default', CPAC_TEXTDOMAIN) . "'>{$width_descr}</div>
							<div class='input-width-range'></div>
							<br/>
							{$more_options}
						</div>
					</li>
				";
			}
		}

		// custom field button
		$button_add_column = '';
		if ( $this->get_meta_by_type($type) )
			$button_add_column = "<a href='javacript:;' class='cpac-add-customfield-column button'>+ " . __('Add Custom Field Column', CPAC_TEXTDOMAIN) . "</a>";

		return "
			<div class='cpac-box'>
				<ul class='cpac-option-list'>
					{$list}
				</ul>
				{$button_add_column}
				<div class='cpac-reorder-msg'>" . __('drag and drop to reorder', CPAC_TEXTDOMAIN) . "</div>
			</div>
			";
	}

	/**
	 * Get merged columns
	 *
	 * @since     1.0
	 */
	protected function get_merged_columns( $type )
	{
		/** Comments */
		if ( $type == 'wp-comments' ) {
			$wp_default_columns = $this->get_wp_default_comments_columns();
			$wp_custom_columns  = $this->get_custom_comments_columns();
		}

		/** Links */
		elseif ( $type == 'wp-links' ) {
			$wp_default_columns = $this->get_wp_default_links_columns();
			$wp_custom_columns  = $this->get_custom_links_columns();
		}

		/** Users */
		elseif ( $type == 'wp-users' ) {
			$wp_default_columns = $this->get_wp_default_users_columns();
			$wp_custom_columns  = $this->get_custom_users_columns();
		}

		/** Media */
		elseif ( $type == 'wp-media' ) {
			$wp_default_columns = $this->get_wp_default_media_columns();
			$wp_custom_columns  = $this->get_custom_media_columns();
		}

		/** Posts */
		else {
			$wp_default_columns = $this->get_wp_default_posts_columns($type);
			$wp_custom_columns  = $this->get_custom_posts_columns($type);
		}

		// merge columns
		$display_columns = $this->parse_columns($wp_custom_columns, $wp_default_columns, $type);

		return $display_columns;
	}

	/**
	 * Merge the default columns (set by WordPress) and the added custom columns (set by plugins, theme etc.)
	 *
	 * @since     1.3.3
	 */
	function parse_columns($wp_custom_columns, $wp_default_columns, $type)
	{
		// merge columns
		$default_columns = wp_parse_args($wp_custom_columns, $wp_default_columns);

		//get saved database columns
		$db_columns = self::get_stored_columns($type);
		if ( $db_columns ) {

			// let's remove any unavailable columns.. such as disabled plugins
			$db_columns 	 = $this->remove_unavailable_columns($db_columns, $default_columns);

			// loop throught the active columns
			foreach ( $db_columns as $id => $values ) {

				// get column meta options from custom columns
				if ( $this->is_column_meta($id) && !empty($wp_custom_columns['column-meta-1']['options']) ) {
					$db_columns[$id]['options'] = $wp_custom_columns['column-meta-1']['options'];
				}

				// add static options
				elseif ( isset($default_columns[$id]['options']) )
					$db_columns[$id]['options'] = $default_columns[$id]['options'];

				unset($default_columns[$id]);
			}
		}

		// merge all
		return wp_parse_args($db_columns, $default_columns);
	}

	/**
	 * Remove deactivated (plugin) columns
	 *
	 * This will remove any columns that have been stored, but are no longer available. This happends
	 * when plugins are deactivated or when they are removed from the theme functions.
	 *
	 * @since     1.2
	 */
	private function remove_unavailable_columns( array $db_columns, array $default_columns)
	{
		// check or differences
		$diff = array_diff( array_keys($db_columns), array_keys($default_columns) );

		if ( ! empty($diff) && is_array($diff) ) {
			foreach ( $diff as $column_name ){
				// make an exception for column-meta-xxx
				if ( ! $this->is_column_meta($column_name) ) {
					unset($db_columns[$column_name]);
				}
			}
		}

		return $db_columns;
	}

	/**
	 * Get checkbox
	 *
	 * @since     1.0
	 */
	private function get_box($type, $id, $values)
	{
		$classes = array();

		// set state
		$state 	= isset($values['state']) ? $values['state'] : '';

		// class
		$classes[] = "cpac-box-{$id}";
		if ( $state ) {
			$classes[] = 'active';
		}
		if ( ! empty($values['options']['class']) ) {
			$classes[] = $values['options']['class'];
		}
		$class = implode(' ', $classes);

		// more box options
		$more_options 	= $this->get_additional_box_options($type, $id, $values);
		$action 		= "<a class='cpac-action' href='#open'>open</a>";

		// type label
		$type_label = isset($values['options']['type_label']) ? $values['options']['type_label'] : '';

		// label
		$label = isset($values['label']) ? str_replace("'", '"', $values['label']) : '';

		// main label
		$main_label = $values['label'];

		// main label exception for comments
		if ( 'comments' == $id ) {
			$main_label = $this->get_comment_icon();
		}

		// width
		$width			= isset($values['width']) ? $values['width'] : 0;
		$width_descr	= isset($values['width']) && $values['width'] > 0 ? $values['width'] . '%' : __('default', CPAC_TEXTDOMAIN);

		// hide box options
		$label_hidden = '';
		if ( ! empty($values['options']['hide_options']) || strpos($label, '<img') !== false ) {
			$label_hidden = ' style="display:none"';
		}

		$list = "
			<li class='{$class}'>
				<div class='cpac-sort-handle'></div>
				<div class='cpac-type-options'>
					<div class='cpac-checkbox'></div>
					<input type='hidden' class='cpac-state' name='cpac_options[columns][{$type}][{$id}][state]' value='{$state}'/>
					<label class='main-label'>{$main_label}</label>
				</div>
				<div class='cpac-meta-title'>
					{$action}
					<span>{$type_label}</span>
				</div>
				<div class='cpac-type-inside'>
					<label for='cpac_options-{$type}-{$id}-label'{$label_hidden}>Label: </label>
					<input type='text' name='cpac_options[columns][{$type}][{$id}][label]' id='cpac_options-{$type}-{$id}-label' value='{$label}' class='text'{$label_hidden}/>
					<label for='cpac_options-{$type}-{$id}-width'>" . __('Width', CPAC_TEXTDOMAIN) . ":</label>
					<input type='hidden' maxlength='4' class='input-width' name='cpac_options[columns][{$type}][{$id}][width]' id='cpac_options-{$type}-{$id}-width' value='{$width}' />
					<div class='description width-decription' title='" . __('default', CPAC_TEXTDOMAIN) . "'>{$width_descr}</div>
					<div class='input-width-range'></div>
					<br/>
					{$more_options}
				</div>
			</li>
		";

		return $list;
	}

	/**
	 * Get additional box option fields
	 *
	 * @since     1.0
	 */
	private function get_additional_box_options($type, $id, $values)
	{
		$fields = '';

		// Custom Fields
		if( $this->is_column_meta($id) ) {
			$fields = $this->get_box_options_customfields($type, $id, $values);
		}

		// Author Fields
		if( 'column-author-name' == $id) {
			$fields = $this->get_box_options_author($type, $id, $values);
		}

		return $fields;
	}

	/**
	 * Box Options: Custom Fields
	 *
	 * @since     1.0
	 */
	private function get_box_options_customfields($type, $id, $values)
	{
		// get post meta fields
		$fields = $this->get_meta_by_type($type);

		if ( empty($fields) )
			return false;

		// set meta field options
		$current = ! empty($values['field']) ? $values['field'] : '' ;
		$field_options = '';
		foreach ($fields as $field) {

			$field_options .= sprintf
			(
				'<option value="%s"%s>%s</option>',
				$field,
				$field == $current? ' selected="selected"':'',

				// change label on hidden fields
				substr($field,0,10) == "cpachidden" ? str_replace('cpachidden','',$field) : $field
			);
		}

		// set meta fieldtype options
		$currenttype = ! empty($values['field_type']) ? $values['field_type'] : '' ;
		$fieldtype_options = '';
		$fieldtypes = array(
			''				=> __('Default'),
			'image'			=> __('Image'),
			'library_id'	=> __('Media Library Icon', CPAC_TEXTDOMAIN),
			'excerpt'		=> __('Excerpt'),
			'array'			=> __('Multiple Values', CPAC_TEXTDOMAIN),
			'numeric'		=> __('Numeric', CPAC_TEXTDOMAIN),
			'date'			=> __('Date', CPAC_TEXTDOMAIN),
			'title_by_id'	=> __('Post Title (Post ID\'s)', CPAC_TEXTDOMAIN),
			'user_by_id'	=> __('Username (User ID\'s)', CPAC_TEXTDOMAIN),
			'checkmark'		=> __('Checkmark (true/false)', CPAC_TEXTDOMAIN),
			'color'			=> __('Color', CPAC_TEXTDOMAIN),
		);

		// add filter
		$fieldtypes = apply_filters('cpac-field-types', $fieldtypes );

		// set select options
		foreach ( $fieldtypes as $fkey => $fieldtype ) {
			$fieldtype_options .= sprintf
			(
				'<option value="%s"%s>%s</option>',
				$fkey,
				$fkey == $currenttype? ' selected="selected"':'',
				$fieldtype
			);
		}

		// before and after string
		$before = ! empty($values['before']) 	? $values['before'] : '' ;
		$after 	= ! empty($values['after']) 	? $values['after'] 	: '' ;

		if ( empty($field_options) )
			return false;

		// add remove button
		$remove = '<p class="remove-description description">'.__('This field can not be removed', CPAC_TEXTDOMAIN).'</p>';
		if ( $id != 'column-meta-1') {
			$remove = "
				<p>
					<a href='javascript:;' class='cpac-delete-custom-field-box'>".__('Remove')."</a>
				</p>
			";
		}

		$inside = "
			<label for='cpac-{$type}-{$id}-field'>".__('Custom Field', CPAC_TEXTDOMAIN).": </label>
			<select name='cpac_options[columns][{$type}][{$id}][field]' id='cpac-{$type}-{$id}-field'>{$field_options}</select>
			<br/>
			<label for='cpac-{$type}-{$id}-field_type'>".__('Field Type', CPAC_TEXTDOMAIN).": </label>
			<select name='cpac_options[columns][{$type}][{$id}][field_type]' id='cpac-{$type}-{$id}-field_type'>{$fieldtype_options}</select>
			<br/>
			<label for='cpac-{$type}-{$id}-before'>".__('Before', CPAC_TEXTDOMAIN).": </label>
			<input type='text' class='cpac-before' name='cpac_options[columns][{$type}][{$id}][before]' id='cpac-{$type}-{$id}-before' value='{$before}'/>
			<br/>
			<label for='cpac-{$type}-{$id}-after'>".__('After', CPAC_TEXTDOMAIN).": </label>
			<input type='text' class='cpac-after' name='cpac_options[columns][{$type}][{$id}][after]' id='cpac-{$type}-{$id}-after' value='{$after}'/>
			<br/>
			{$remove}
		";

		return $inside;
	}

	/**
	 * Box Options: Custom Fields
	 *
	 * @since     1.0
	 */
	private function get_box_options_author($type, $id, $values)
	{
		$options = '';
		$author_types = array(
			'display_name'		=> __('Display Name', CPAC_TEXTDOMAIN),
			'first_name'		=> __('First Name', CPAC_TEXTDOMAIN),
			'last_name'			=> __('Last Name', CPAC_TEXTDOMAIN),
			'first_last_name' 	=> __('First &amp; Last Name', CPAC_TEXTDOMAIN),
			'nickname'			=> __('Nickname', CPAC_TEXTDOMAIN),
			'username'			=> __('Username', CPAC_TEXTDOMAIN),
			'email'				=> __('Email', CPAC_TEXTDOMAIN),
			'userid'			=> __('User ID', CPAC_TEXTDOMAIN)
		);
		$currentname = ! empty($values['display_as']) ? $values['display_as'] : '' ;
		foreach ( $author_types as $k => $name ) {
			$selected = selected( $k, $currentname, false);
			$options .= "<option value='{$k}' {$selected}>{$name}</option>";
		}

		$inside = "
			<label for='cpac-{$type}-{$id}-display_as'>".__('Display name as', CPAC_TEXTDOMAIN).": </label>
			<select name='cpac_options[columns][{$type}][{$id}][display_as]' id='cpac-{$type}-{$id}-display_as'>
				{$options}
			</select>
		";

		return $inside;
	}

	/**
	 * Get post meta fields by type; post(types) or users.
	 *
	 * @since     1.0
	 */
	private function get_meta_by_type($type = 'post')
	{
		global $wpdb;

		/** Comments */
		if ( $type == 'wp-comments') {
			$sql = "SELECT DISTINCT meta_key FROM {$wpdb->commentmeta} ORDER BY 1";
		}

		/** Users */
		elseif ( $type == 'wp-users') {
			$sql = "SELECT DISTINCT meta_key FROM {$wpdb->usermeta} ORDER BY 1";
		}

		/** Media */
		elseif ( $type == 'wp-media') {
			$sql = "SELECT DISTINCT meta_key FROM {$wpdb->postmeta} pm JOIN {$wpdb->posts} p ON pm.post_id = p.ID WHERE p.post_type = 'attachment' ORDER BY 1";
		}

		/** Posts */
		else {
			$sql = $wpdb->prepare( "SELECT DISTINCT meta_key FROM {$wpdb->postmeta} pm JOIN {$wpdb->posts} p ON pm.post_id = p.ID WHERE p.post_type = %s ORDER BY 1", $type);
		}

		// run sql
		$fields = $wpdb->get_results($sql, ARRAY_N);

		// filter out hidden meta fields
		$meta_fields = array();
		if ( $fields ) {
			foreach ($fields as $field) {

				// give hidden fields a prefix for identifaction
				if ( $this->use_hidden_custom_fields && substr($field[0],0,1) == "_") {
					$meta_fields[] = 'cpachidden'.$field[0];
				}

				// non hidden fields are saved as is
				elseif ( substr($field[0],0,1) != "_" ) {
					$meta_fields[] = $field[0];
				}
			}
		}

		if ( empty($meta_fields) )
			$meta_fields = false;

		return apply_filters( 'cpac-get-meta-by-type', $meta_fields, $type );
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
	 *	Get column types
	 *
	 * 	@since     1.1
	 */
	private function get_types()
	{
		$types 					= $this->post_types;
		$types['wp-users'] 		= 'wp-users';
		$types['wp-media'] 		= 'wp-media';
		$types['wp-links'] 		= 'wp-links';
		$types['wp-comments'] 	= 'wp-comments';

		return $types;
	}

	/**
	 * Get post types
	 *
	 * @since     1.0
	 */
	public static function get_post_types()
	{
		$post_types = get_post_types(array(
			'_builtin' => false
		));
		$post_types['post'] = 'post';
		$post_types['page'] = 'page';

		return apply_filters('cpac-get-post-types', $post_types);
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
	 * Register column css
	 *
	 * @since     1.0
	 */
	public function column_styles()
	{
		wp_enqueue_style( 'cpac-columns', CPAC_URL.'/assets/css/column.css', array(), CPAC_VERSION, 'all' );
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
			add_option( 'cpac_options', $this->get_default_plugin_options() );
		}

		register_setting( 'cpac-settings-group', 'cpac_options', array($this, 'options_callback') );
	}

	/**
	 * Returns the default plugin options.
	 *
	 * @since     1.0
	 */
	public function get_default_plugin_options()
	{
		return apply_filters( 'cpac_default_plugin_options', array() );
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
	 * Handle requests.
	 *
	 * @since     1.0
	 */
	public function handle_requests()
	{
		// only handle updates from the admin columns page
		if ( isset($_REQUEST['page']) && CPAC_SLUG == $_REQUEST['page'] ) {

			// settings updated
			if ( ! empty($_REQUEST['settings-updated']) ) {
				$this->store_wp_default_columns();
			}

			// restore defaults
			if ( ! empty($_REQUEST['cpac-restore-defaults']) ) {
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
		foreach ( $this->post_types as $post_type ) {
			$wp_default_columns[$post_type] = $this->get_wp_default_posts_columns($post_type);
		}

		// Users
		$wp_default_columns['wp-users'] = $this->get_wp_default_users_columns();

		// Media
		$wp_default_columns['wp-media'] = $this->get_wp_default_media_columns();

		// Links
		$wp_default_columns['wp-links'] = $this->get_wp_default_links_columns();

		// Comments
		$wp_default_columns['wp-comments'] = $this->get_wp_default_comments_columns();

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
	 * Get author field by nametype
	 *
	 * Used by posts and sortable
	 *
	 * @since     1.4.6.1
	 */
	public function get_author_field_by_nametype( $nametype, $user_id)
	{
		$userdata = get_userdata( $user_id );

		switch ( $nametype ) :

			case "display_name" :
				$name = $userdata->display_name;
				break;

			case "first_name" :
				$name = $userdata->first_name;
				break;

			case "last_name" :
				$name = $userdata->last_name;
				break;

			case "first_last_name" :
				$first = !empty($userdata->first_name) ? $userdata->first_name : '';
				$last = !empty($userdata->last_name) ? " {$userdata->last_name}" : '';
				$name = $first.$last;
				break;

			case "nickname" :
				$name = $userdata->nickname;
				break;

			case "username" :
				$name = $userdata->user_login;
				break;

			case "email" :
				$name = $userdata->user_email;
				break;

			case "userid" :
				$name = $userdata->ID;
				break;

			default :
				$name = $userdata->display_name;

		endswitch;

		return $name;
	}

	/**
	 * 	Get WP default supported admin columns per post type.
	 *
	 * 	@since     1.0
	 */
	private function get_wp_default_posts_columns($post_type = 'post')
	{
		// You can use this filter to add thirdparty columns by hooking into this. See classes/third_party.php for an example.
		do_action( 'cpac-get-default-columns-posts', $post_type );

		// some plugins directly hook into get_column_headers, such as woocommerce
		$columns = get_column_headers( 'edit-'.$post_type );

		// get default columns
		if ( empty($columns) ) {

			// deprecated as of wp3.3
			if ( file_exists(ABSPATH . 'wp-admin/includes/template.php') )
				require_once(ABSPATH . 'wp-admin/includes/template.php');

			// introduced since wp3.3
			if ( file_exists(ABSPATH . 'wp-admin/includes/screen.php') )
				require_once(ABSPATH . 'wp-admin/includes/screen.php');

			// used for getting columns
			if ( file_exists(ABSPATH . 'wp-admin/includes/class-wp-list-table.php') )
				require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
			if ( file_exists(ABSPATH . 'wp-admin/includes/class-wp-posts-list-table.php') )
				require_once(ABSPATH . 'wp-admin/includes/class-wp-posts-list-table.php');

			// As of WP Release 3.5 we can use the following.
			if ( version_compare( get_bloginfo('version'), '3.4.10', '>=' ) ) {

				$table 		= new WP_Posts_List_Table( array( 'screen' => $post_type ) );
				$columns 	= $table->get_columns();
			}

			// WP versions older then 3.5
			// @todo: make this deprecated
			else {

				// we need to change the current screen... first lets save original
				$org_current_screen = $current_screen;

				// prevent php warning
				if ( !isset($current_screen) ) $current_screen = new stdClass;

				// overwrite current_screen global with our post type of choose...
				$current_screen->post_type = $post_type;

				// ...so we can get its columns
				$columns = WP_Posts_List_Table::get_columns();

				// reset current screen
				$current_screen = $org_current_screen;
			}
		}

		if ( empty ( $columns ) )
			return false;

		// change to uniform format
		$columns = $this->get_uniform_format($columns);

		// add sorting to some of the default links columns
		$columns = $this->set_sorting_to_default_posts_columns($columns);

		return $columns;
	}

	/**
	 * 	Add Sorting to WP default Posts columns
	 *
	 * 	@since     1.4.5
	 */
	private function set_sorting_to_default_posts_columns($columns)
	{
		//	categories
		if ( !empty($columns['categories']) ) {
			$columns['categories']['options']['sortorder'] = 'on';
		}
		// tags
		if ( !empty($columns['tags']) ) {
			$columns['tags']['options']['sortorder'] = 'on';
		}
		return $columns;
	}

	/**
	 * 	Get WP default users columns per post type.
	 *
	 * 	@since     1.1
	 */
	private function get_wp_default_users_columns()
	{
		// You can use this filter to add third_party columns by hooking into this.
		do_action( 'cpac-get-default-columns-users' );

		if ( file_exists(ABSPATH . 'wp-admin/includes/class-wp-list-table.php') )
			require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
		if ( file_exists(ABSPATH . 'wp-admin/includes/class-wp-users-list-table.php') )
			require_once(ABSPATH . 'wp-admin/includes/class-wp-users-list-table.php');

		// turn off site users
		$this->is_site_users = false;

		// get users columns
		$columns = WP_Users_List_Table::get_columns();

		// change to uniform format
		$columns = $this->get_uniform_format($columns);

		return apply_filters('cpac-default-users-columns', $columns);
	}

	/**
	 * 	Get WP default media columns.
	 *
	 * 	@since     1.2.1
	 */
	private function get_wp_default_media_columns()
	{
		// You can use this filter to add third_party columns by hooking into this.
		do_action( 'cpac-get-default-columns-media' );

		// @todo could use _get_list_table('WP_Media_List_Table') ?
		if ( file_exists(ABSPATH . 'wp-admin/includes/class-wp-list-table.php') )
			require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
		if ( file_exists(ABSPATH . 'wp-admin/includes/class-wp-media-list-table.php') )
			require_once(ABSPATH . 'wp-admin/includes/class-wp-media-list-table.php');

		// As of WP Release 3.5 we can use the following.
		if ( version_compare( get_bloginfo('version'), '3.4.10', '>=' ) ) {

			$table 		= new WP_Media_List_Table(array( 'screen' => 'upload' ));
			$columns 	= $table->get_columns();
		}

		// WP versions older then 3.5
		// @todo: make this deprecated
		else {

			global $current_screen;

			// save original
			$org_current_screen = $current_screen;

			// prevent php warning
			if ( !isset($current_screen) ) $current_screen = new stdClass;

			// overwrite current_screen global with our media id...
			$current_screen->id = 'upload';

			// init media class
			$wp_media = new WP_Media_List_Table;

			// get media columns
			$columns = $wp_media->get_columns();

			// reset current screen
			$current_screen = $org_current_screen;
		}

		// change to uniform format
		$columns = $this->get_uniform_format($columns);

		return apply_filters('cpac-default-media-columns', $columns);
	}

	/**
	 * 	Get WP default links columns.
	 *
	 * 	@since     1.3.1
	 */
	private function get_wp_default_links_columns()
	{
		// You can use this filter to add third_party columns by hooking into this.
		do_action( 'cpac-get-default-columns-links' );

		// dependencies
		if ( file_exists(ABSPATH . 'wp-admin/includes/class-wp-list-table.php') )
			require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
		if ( file_exists(ABSPATH . 'wp-admin/includes/class-wp-links-list-table.php') )
			require_once(ABSPATH . 'wp-admin/includes/class-wp-links-list-table.php');

		// get links columns
		$columns = WP_Links_List_Table::get_columns();

		// change to uniform format
		$columns = $this->get_uniform_format($columns);

		// add sorting to some of the default links columns
		$columns = $this->set_sorting_to_default_links_columns($columns);

		return apply_filters('cpac-default-links-columns', $columns);
	}

	/**
	 * 	Add Sorting to WP default links columns
	 *
	 * 	@since     1.4
	 */
	private function set_sorting_to_default_links_columns($columns)
	{
		// Relationship
		if ( !empty($columns['rel']) ) {
			$columns['rel']['options']['sortorder'] = 'on';
		}
		return $columns;
	}

	/**
	 * 	Get WP default links columns.
	 *
	 * 	@since     1.3.1
	 */
	private function get_wp_default_comments_columns()
	{
		// You can use this filter to add third_party columns by hooking into this.
		do_action( 'cpac-get-default-columns-comments' );

		// dependencies
		if ( file_exists(ABSPATH . 'wp-admin/includes/class-wp-list-table.php') )
			require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
		if ( file_exists(ABSPATH . 'wp-admin/includes/class-wp-comments-list-table.php') )
			require_once(ABSPATH . 'wp-admin/includes/class-wp-comments-list-table.php');

		// As of WP Release 3.5 we can use the following.
		if ( version_compare( get_bloginfo('version'), '3.4.10', '>=' ) ) {

			$table 		= new WP_Comments_List_Table( array( 'screen' => 'edit-comments' ) );
			$columns 	= $table->get_columns();
		}

		// WP versions older then 3.5
		// @todo: make this deprecated
		else {

			global $current_screen;

			// save original
			$org_current_screen = $current_screen;

			// prevent php warning
			if ( !isset($current_screen) ) $current_screen = new stdClass;

			// overwrite current_screen global with our media id...
			$current_screen->id = 'edit-comments';

			// init table object
			$wp_comment = new WP_Comments_List_Table;

			// get comments
			$columns = $wp_comment->get_columns();

			// reset current screen
			$current_screen = $org_current_screen;
		}

		// change to uniform format
		$columns = $this->get_uniform_format($columns);

		// add sorting to some of the default links columns
		$columns = $this->set_sorting_to_default_comments_columns($columns);

		return apply_filters('cpac-default-comments-columns', $columns);
	}

	/**
	 * 	Add Sorting to WP default comments columns
	 *
	 * 	@since     1.4
	 */
	private function set_sorting_to_default_comments_columns($columns)
	{
		// Comment
		if ( !empty($columns['comment']) ) {
			$columns['comment']['options']['sortorder'] = 'on';
		}
		return $columns;
	}

	/**
	 * Build uniform format for all columns
	 *
	 * @since     1.0
	 */
	private function get_uniform_format($columns)
	{
		// we remove the checkbox column as an option...
		if ( isset($columns['cb']) )
			unset($columns['cb']);

		// change to uniform format
		$uniform_columns = array();
		foreach ( (array) $columns as $id => $label ) {
			$hide_options 	= false;
			$type_label 	= $label;

			// comment exception
			if ( 'comments' == $id ) {
				$label 			= '';
				$type_label 	= __('Comments', CPAC_TEXTDOMAIN);
				$hide_options 	= true;
			}

			// user icon exception
			if ( $id == 'icon' ) {
				$type_label 	= __('Icon', CPAC_TEXTDOMAIN);
			}

			$uniform_columns[$id] = array(
				'label'			=> $label,
				'state'			=> 'on',
				'options'		=> array(
					'type_label' 	=> $type_label,
					'hide_options'	=> $hide_options,
					'class'			=> 'cpac-box-wp-native',
				)
			);
		}
		return $uniform_columns;
	}

	/**
	 * Custom posts columns
	 *
	 * @since     1.0
	 */
	private function get_custom_posts_columns($post_type)
	{
		$custom_columns = array(
			'column-featured_image' => array(
				'label'	=> __('Featured Image', CPAC_TEXTDOMAIN)
			),
			'column-excerpt' => array(
				'label'	=> __('Excerpt', CPAC_TEXTDOMAIN)
			),
			'column-order' => array(
				'label'	=> __('Page Order', CPAC_TEXTDOMAIN)
			),
			'column-post_formats' => array(
				'label'	=> __('Post Format', CPAC_TEXTDOMAIN)
			),
			'column-postid' => array(
				'label'	=> __('ID', CPAC_TEXTDOMAIN)
			),
			'column-page-slug' => array(
				'label'	=> __('Slug', CPAC_TEXTDOMAIN)
			),
			'column-attachment' => array(
				'label'	=> __('Attachment', CPAC_TEXTDOMAIN)
			),
			'column-attachment-count' => array(
				'label'	=> __('No. of Attachments', CPAC_TEXTDOMAIN)
			),
			'column-roles' => array(
				'label'	=> __('Roles', CPAC_TEXTDOMAIN)
			),
			'column-status' => array(
				'label'	=> __('Status', CPAC_TEXTDOMAIN)
			),
			'column-comment-status' => array(
				'label'	=> __('Comment status', CPAC_TEXTDOMAIN)
			),
			'column-ping-status' => array(
				'label'	=> __('Ping status', CPAC_TEXTDOMAIN)
			),
			'column-actions' => array(
				'label'	=> __('Actions', CPAC_TEXTDOMAIN),
				'options'	=> array(
					'sortorder'	=> false
				)
			),
			'column-modified' => array(
				'label'	=> __('Last modified', CPAC_TEXTDOMAIN)
			),
			'column-comment-count' => array(
				'label'	=> __('Comment count', CPAC_TEXTDOMAIN)
			),
			'column-author-name' => array(
				'label'			=> __('Display Author As', CPAC_TEXTDOMAIN),
				'display_as'	=> ''
			),
			'column-before-moretag' => array(
				'label'	=> __('Before More Tag', CPAC_TEXTDOMAIN)
			)
		);

		// Word count support
		if ( post_type_supports($post_type, 'editor') ) {
			$custom_columns['column-word-count'] = array(
				'label'	=> __('Word count', CPAC_TEXTDOMAIN)
			);
		}

		// Sticky support
		if ( $post_type == 'post' ) {
			$custom_columns['column-sticky'] = array(
				'label'			=> __('Sticky', CPAC_TEXTDOMAIN)
			);
		}

		// Order support
		if ( post_type_supports($post_type, 'page-attributes') ) {
			$custom_columns['column-order'] = array(
				'label'			=> __('Page Order', CPAC_TEXTDOMAIN),
				'options'		=> array(
					'type_label' 	=> __('Order', CPAC_TEXTDOMAIN)
				)
			);
		}

		// Page Template
		if ( $post_type == 'page' ) {
			$custom_columns['column-page-template'] = array(
				'label'	=> __('Page Template', CPAC_TEXTDOMAIN)
			);
		}

		// Post Formats
		if ( post_type_supports($post_type, 'post-formats') ) {
			$custom_columns['column-post_formats'] = array(
				'label'	=> __('Post Format', CPAC_TEXTDOMAIN)
			);
		}

		// Taxonomy support
		$taxonomies = get_object_taxonomies($post_type, 'objects');
		if ( $taxonomies ) {
			foreach ( $taxonomies as $tax_slug => $tax ) {
				if ( $tax_slug != 'post_tag' && $tax_slug != 'category' && $tax_slug != 'post_format' ) {
					$custom_columns['column-taxonomy-'.$tax->name] = array(
						'label'			=> $tax->label,
						'show_filter'	=> true,
						'options'		=> array(
							'type_label'	=> __('Taxonomy', CPAC_TEXTDOMAIN)
						)
					);
				}
			}
		}

		// Custom Field support
		if ( $this->get_meta_by_type($post_type) ) {
			$custom_columns['column-meta-1'] = array(
				'label'			=> __('Custom Field', CPAC_TEXTDOMAIN),
				'field'			=> '',
				'field_type'	=> '',
				'before'		=> '',
				'after'			=> '',
				'options'		=> array(
					'type_label'	=> __('Field', CPAC_TEXTDOMAIN),
					'class'			=> 'cpac-box-metafield'
				)
			);
		}

		// merge with defaults
		$custom_columns = $this->parse_defaults($custom_columns);

		return apply_filters('cpac-custom-posts-columns', $custom_columns);
	}

	/**
	 * Custom users columns
	 *
	 * @since     1.1
	 */
	private function get_custom_users_columns()
	{
		$custom_columns = array(
			'column-user_id' => array(
				'label'	=> __('User ID', CPAC_TEXTDOMAIN)
			),
			'column-nickname' => array(
				'label'	=> __('Nickname', CPAC_TEXTDOMAIN)
			),
			'column-first_name' => array(
				'label'	=> __('First name', CPAC_TEXTDOMAIN)
			),
			'column-last_name' => array(
				'label'	=> __('Last name', CPAC_TEXTDOMAIN)
			),
			'column-user_url' => array(
				'label'	=> __('Url', CPAC_TEXTDOMAIN)
			),
			'column-user_registered' => array(
				'label'	=> __('Registered', CPAC_TEXTDOMAIN)
			),
			'column-user_description' => array(
				'label'	=> __('Description', CPAC_TEXTDOMAIN)
			),
			'column-actions' => array(
				'label'	=> __('Actions', CPAC_TEXTDOMAIN),
				'options'	=> array(
					'sortorder'	=> false
				)
			),
		);

		// User total number of posts
		foreach ( self::get_post_types() as $post_type ) {
			$label = $this->get_plural_name($post_type);
			$custom_columns['column-user_postcount-'.$post_type] = array(
				'label'			=> __( sprintf('No. of %s',$label), CPAC_TEXTDOMAIN),
				'options'		=> array(
					'type_label'	=> __('Postcount', CPAC_TEXTDOMAIN)
				)
			);
		}

		// Custom Field support
		$custom_columns['column-meta-1'] = array(
			'label'			=> __('Custom Field', CPAC_TEXTDOMAIN),
			'field'			=> '',
			'field_type'	=> '',
			'before'		=> '',
			'after'			=> '',
			'options'		=> array(
				'type_label'	=> __('Field', CPAC_TEXTDOMAIN),
				'class'			=> 'cpac-box-metafield'
			)
		);

		// merge with defaults
		$custom_columns = $this->parse_defaults($custom_columns);

		return apply_filters('cpac-custom-users-columns', $custom_columns);
	}

	/**
	 * Custom media columns
	 *
	 * @since     1.3
	 */
	private function get_custom_media_columns()
	{
		$custom_columns = array(
			'column-mediaid' => array(
				'label'	=> __('ID', CPAC_TEXTDOMAIN)
			),
			'column-mime_type' => array(
				'label'	=> __('Mime type', CPAC_TEXTDOMAIN)
			),
			'column-file_name' => array(
				'label'	=> __('File name', CPAC_TEXTDOMAIN)
			),
			'column-dimensions' => array(
				'label'	=> __('Dimensions', CPAC_TEXTDOMAIN)
			),
			'column-height' => array(
				'label'	=> __('Height', CPAC_TEXTDOMAIN)
			),
			'column-width' => array(
				'label'	=> __('Width', CPAC_TEXTDOMAIN)
			),
			'column-caption' => array(
				'label'	=> __('Caption', CPAC_TEXTDOMAIN)
			),
			'column-description' => array(
				'label'	=> __('Description', CPAC_TEXTDOMAIN)
			),
			'column-alternate_text' => array(
				'label'	=> __('Alt', CPAC_TEXTDOMAIN)
			),
			'column-file_paths' => array(
				'label'	=> __('Upload paths', CPAC_TEXTDOMAIN),
				'options'	=> array(
					'sortorder'	=> false
				)
			),
			'column-actions' => array(
				'label'	=> __('Actions', CPAC_TEXTDOMAIN),
				'options'	=> array(
					'sortorder'	=> false
				)
			),
			'column-filesize' => array(
				'label'	=> __('File size', CPAC_TEXTDOMAIN)
			)
		);

		// Get extended image metadata, exif or iptc as available.
		// uses exif_read_data()
		if ( function_exists('exif_read_data') ) {
			$custom_columns = array_merge( $custom_columns, array(
				'column-image-aperture' => array(
					'label'		=> __('Aperture', CPAC_TEXTDOMAIN),
					'options'	=> array(
						'type_label'	=> __('Aperture EXIF', CPAC_TEXTDOMAIN)
					)
				),
				'column-image-credit' => array(
					'label'		=> __('Credit', CPAC_TEXTDOMAIN),
					'options'	=> array(
						'type_label'	=> __('Credit EXIF', CPAC_TEXTDOMAIN)
					)
				),
				'column-image-camera' => array(
					'label'		=> __('Camera', CPAC_TEXTDOMAIN),
					'options'	=> array(
						'type_label'	=> __('Camera EXIF', CPAC_TEXTDOMAIN)
					)
				),
				'column-image-caption' => array(
					'label'		=> __('Caption', CPAC_TEXTDOMAIN),
					'options'	=> array(
						'type_label'	=> __('Caption EXIF', CPAC_TEXTDOMAIN)
					)
				),
				'column-image-created_timestamp' => array(
					'label'		=> __('Timestamp', CPAC_TEXTDOMAIN),
					'options'	=> array(
						'type_label'	=> __('Timestamp EXIF', CPAC_TEXTDOMAIN)
					)
				),
				'column-image-copyright' => array(
					'label'		=> __('Copyright', CPAC_TEXTDOMAIN),
					'options'	=> array(
						'type_label'	=> __('Copyright EXIF', CPAC_TEXTDOMAIN)
					)
				),
				'column-image-focal_length' => array(
					'label'		=> __('Focal Length', CPAC_TEXTDOMAIN),
					'options'	=> array(
						'type_label'	=> __('Focal Length EXIF', CPAC_TEXTDOMAIN)
					)
				),
				'column-image-iso' => array(
					'label'		=> __('ISO', CPAC_TEXTDOMAIN),
					'options'	=> array(
						'type_label'	=> __('ISO EXIF', CPAC_TEXTDOMAIN)
					)
				),
				'column-image-shutter_speed' => array(
					'label'		=> __('Shutter Speed', CPAC_TEXTDOMAIN),
					'options'	=> array(
						'type_label'	=> __('Shutter Speed EXIF', CPAC_TEXTDOMAIN)
					)
				),
				'column-image-title' => array(
					'label'		=> __('Title', CPAC_TEXTDOMAIN),
					'options'	=> array(
						'type_label'	=> __('Title EXIF', CPAC_TEXTDOMAIN)
					)
				)
			));
		}

		// Custom Field support
		if ( $this->get_meta_by_type('wp-media') ) {
			$custom_columns['column-meta-1'] = array(
				'label'			=> __('Custom Field', CPAC_TEXTDOMAIN),
				'field'			=> '',
				'field_type'	=> '',
				'before'		=> '',
				'after'			=> '',
				'options'		=> array(
					'type_label'	=> __('Field', CPAC_TEXTDOMAIN),
					'class'			=> 'cpac-box-metafield'
				)
			);
		}

		// merge with defaults
		$custom_columns = $this->parse_defaults($custom_columns);

		return apply_filters('cpac-custom-media-columns', $custom_columns);
	}

	/**
	 * Custom links columns
	 *
	 * @since     1.3.1
	 */
	private function get_custom_links_columns()
	{
		$custom_columns = array(
			'column-link_id' => array (
				'label'	=> __('ID', CPAC_TEXTDOMAIN)
			),
			'column-description' => array (
				'label'	=> __('Description', CPAC_TEXTDOMAIN)
			),
			'column-image' => array(
				'label'	=> __('Image', CPAC_TEXTDOMAIN)
			),
			'column-target' => array(
				'label'	=> __('Target', CPAC_TEXTDOMAIN)
			),
			'column-owner' => array(
				'label'	=> __('Owner', CPAC_TEXTDOMAIN)
			),
			'column-notes' => array(
				'label'	=> __('Notes', CPAC_TEXTDOMAIN)
			),
			'column-rss' => array(
				'label'	=> __('Rss', CPAC_TEXTDOMAIN)
			),
			'column-length' => array(
				'label'	=> __('Length', CPAC_TEXTDOMAIN)
			),
			'column-actions' => array(
				'label'	=> __('Actions', CPAC_TEXTDOMAIN),
				'options'	=> array(
					'sortorder'	=> false
				)
			)
		);

		// merge with defaults
		$custom_columns = $this->parse_defaults($custom_columns);

		return apply_filters('cpac-custom-links-columns', $custom_columns);
	}

	/**
	 * Custom comments columns
	 *
	 * @since     1.3.1
	 */
	private function get_custom_comments_columns()
	{
		$custom_columns = array(
			'column-comment_id' => array(
				'label'	=> __('ID', CPAC_TEXTDOMAIN)
			),
			'column-author_author' => array(
				'label'	=> __('Author Name', CPAC_TEXTDOMAIN)
			),
			'column-author_avatar' => array(
				'label'	=> __('Avatar', CPAC_TEXTDOMAIN)
			),
			'column-author_url' => array(
				'label'	=> __('Author url', CPAC_TEXTDOMAIN)
			),
			'column-author_ip' => array(
				'label'	=> __('Author IP', CPAC_TEXTDOMAIN)
			),
			'column-author_email' => array(
				'label'	=> __('Author email', CPAC_TEXTDOMAIN)
			),
			'column-reply_to' => array(
				'label'			=> __('In Reply To', CPAC_TEXTDOMAIN),
				'options'		=> array(
					'sortorder'		=> false
				)
			),
			'column-approved' => array(
				'label'	=> __('Approved', CPAC_TEXTDOMAIN)
			),
			'column-date' => array(
				'label'	=> __('Date', CPAC_TEXTDOMAIN)
			),
			'column-date_gmt' => array(
				'label'	=> __('Date GMT', CPAC_TEXTDOMAIN)
			),
			'column-agent' => array(
				'label'	=> __('Agent', CPAC_TEXTDOMAIN)
			),
			'column-excerpt' => array(
				'label'	=> __('Excerpt', CPAC_TEXTDOMAIN)
			),
			'column-actions' => array(
				'label'	=> __('Actions', CPAC_TEXTDOMAIN),
				'options'	=> array(
					'sortorder'	=> false
				)
			),
			'column-word-count' => array(
				'label'	=> __('Word count', CPAC_TEXTDOMAIN),
				'options'	=> array(
					'sortorder'	=> false
				)
			)
		);

		// Custom Field support
		if ( $this->get_meta_by_type('wp-comments') ) {
			$custom_columns['column-meta-1'] = array(
				'label'			=> __('Custom Field', CPAC_TEXTDOMAIN),
				'field'			=> '',
				'field_type'	=> '',
				'before'		=> '',
				'after'			=> '',
				'options'		=> array(
					'type_label'	=> __('Field', CPAC_TEXTDOMAIN),
					'class'			=> 'cpac-box-metafield',
					'sortorder'		=> false,
				)
			);
		}

		// merge with defaults
		$custom_columns = $this->parse_defaults($custom_columns);

		return apply_filters('cpac-custom-comments-columns', $custom_columns);
	}

	/**
	 * Parse defaults
	 *
	 * @since     1.1
	 */
	private function parse_defaults($columns)
	{
		// default arguments
		$defaults = array(

			// stored values
			'label'			=> '', // custom label
			'state' 		=> '', // display state
			'width' 		=> '', // column width

			// static values
			'options'		=> array(
				'type_label'	=> __('Custom', CPAC_TEXTDOMAIN),
				'hide_options'	=> false,
				'class'			=> 'cpac-box-custom',
				'sortorder'		=> 'on',
			)
		);

		// parse args
		foreach ( $columns as $k => $column ) {
			$c[$k] = wp_parse_args( $column, $defaults);

			// parse options args
			if ( isset($column['options']) )
				$c[$k]['options'] = wp_parse_args( $column['options'], $defaults['options']);

			// set type label
			if ( empty($column['options']['type_label']) && !empty($column['label']) )
				$c[$k]['options']['type_label']	= $column['label'];
		}

		return $c;
	}

	/**
	 * Admin requests for orderby column
	 *
	 * @since     1.0
	 */
	public static function get_stored_columns($type)
	{
		// get plugin options
		$options 		= get_option('cpac_options');

		// get saved columns
		if ( !empty($options['columns'][$type]) )
			return $options['columns'][$type];

		return false;
	}

	/**
	 * Post Type Menu
	 *
	 * @since     1.0
	 */
	private function get_menu()
	{
		// set
		$menu 	= '';
		$count 	= 1;

		// referer
		$referer = ! empty($_REQUEST['cpac_type']) ? $_REQUEST['cpac_type'] : '';

		// loop
		foreach ( $this->get_types() as $type ) {
			$label 		 = $this->get_singular_name($type);
			$clean_label = $this->sanitize_string($type);

			// divider
			$divider 	= $count++ == 1 ? '' : ' | ';

			// current
			$current = '';
			if ( $this->is_menu_type_current($type) )
				$current = ' class="current"';

			// menu list
			$menu .= "
				<li>{$divider}<a{$current} href='#cpac-box-{$clean_label}'>{$label}</a></li>
			";
		}

		// settings url
		$class_current_settings = $this->is_menu_type_current('plugin_settings') ? ' current': '';

		// options button
		$options_btn = "<a href='#cpac-box-plugin_settings' class='cpac-settings-link{$class_current_settings}'>".__('Addons', CPAC_TEXTDOMAIN)."</a>";
		//$options_btn = '';

		return "
		<div class='cpac-menu'>
			<ul class='subsubsub'>
				{$menu}
			</ul>
			{$options_btn}
		</div>
		";
	}

	/**
	 * Checks if menu type is currently viewed
	 *
	 * @since     1.0
	 */
	private function is_menu_type_current( $type )
	{
		// referer
		$referer = ! empty($_REQUEST['cpac_type']) ? $_REQUEST['cpac_type'] : '';

		// get label
		$clean_label = $this->sanitize_string($type);

		// get first element from post-types
		$first 		= array_shift( array_values($this->post_types) );

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
	 * Get singular name of post type
	 *
	 * @since     1.0
	 */
	private function get_singular_name( $type )
	{
		// Links
		if ( $type == 'wp-links' )
			$label = __('Links');

		// Comments
		elseif ( $type == 'wp-comments' )
			$label = __('Comments');

		// Users
		elseif ( $type == 'wp-users' )
			$label = __('Users');

		// Media
		elseif ( $type == 'wp-media' )
			$label = __('Media Library');

		// Posts
		else {
			$posttype_obj 	= get_post_type_object($type);
			$label 			= $posttype_obj->labels->singular_name;
		}

		return $label;
	}

	/**
	 * Get plural name of post type
	 *
	 * @since     1.3.1
	 */
	private function get_plural_name( $type )
	{
		$posttype_obj = get_post_type_object($type);
		if ( $posttype_obj )
			return $posttype_obj->labels->name;

		return false;
	}

	/**
	 * Get screen link to overview screen
	 *
	 * @since     1.3.1
	 */
	private function get_type_screen_link( $type )
	{
		// Links
		if ( $type == 'wp-comments' )
			$link = get_admin_url() . 'edit-comments.php';

			// Links
		if ( $type == 'wp-links' )
			$link = get_admin_url() . 'link-manager.php';

		// Users
		if ( $type == 'wp-users' )
			$link = get_admin_url() . 'users.php';

		// Media
		elseif ( $type == 'wp-media' )
			$link = get_admin_url() . 'upload.php';

		// Posts
		else
			$link = get_admin_url() . "edit.php?post_type={$type}";

		return $link;
	}

	/**
	 * Sanitize label
	 *
	 * Uses intern wordpress function esc_url so it matches the label sorting url.
	 *
	 * @since     1.0
	 */
	protected function sanitize_string($string)
	{
		$string = esc_url($string);
		$string = str_replace('http://','', $string);
		$string = str_replace('https://','', $string);

		return $string;
	}

	/**
	 * Checks if column-meta key exists
	 *
	 * @since     1.0
	 */
	public static function is_column_meta( $id = '' )
	{
		if ( strpos($id, 'column-meta-') !== false )
			return true;

		return false;
	}

	/**
	 * Get the posttype from columnname
	 *
	 * @since     1.3.1
	 */
	public static function get_posttype_by_postcount_column( $id = '' )
	{
		if ( strpos($id, 'column-user_postcount-') !== false )
			return str_replace('column-user_postcount-', '', $id);

		return false;
	}

	/**
	 *	Get column value of post attachments
	 *
	 * 	@since     1.2.1
	 */
	public static function get_attachment_ids( $post_id )
	{
		return get_posts(array(
			'post_type' 	=> 'attachment',
			'numberposts' 	=> -1,
			'post_status' 	=> null,
			'post_parent' 	=> $post_id,
			'fields' 		=> 'ids'
		));
	}

	/**
	 * Strip tags and trim
	 *
	 * @since     1.3
	 */
	public static function strip_trim($string)
	{
		return trim(strip_tags($string));
	}

	/**
	 * Admin body class
	 *
	 * @since     1.4
	 */
	function admin_class( $classes )
	{
		global $current_screen;

		// we dont need the 'edit-' part
		$screen = str_replace('edit-', '', $current_screen->id);

		// media library exception
		if ( $current_screen->base == 'upload' && $current_screen->id == 'upload' ) {
			$screen = 'media';
		}

		// link exception
		if ( $current_screen->base == 'link-manager' && $current_screen->id == 'link-manager' ) {
			$screen = 'links';
		}

		// loop the available types
		foreach ( $this->get_types() as $type => $label ) {

			// match against screen or wp-screen
			if ( $type == $screen || $type == "wp-{$screen}" )
				$classes .= " cp-{$type}";
		}

		return $classes;
	}


	/**
	 * Admin CSS for Column width
	 *
	 * @since     1.4
	 */
	function admin_css()
	{
		$css = '';

		// loop throug the available types...
		foreach ( $this->get_types() as $type ) {
			$cols = self::get_stored_columns($type);
			if ( $cols ) {

				// loop through each available column...
				foreach ( $cols as $col_name => $col ) {

					// and check for stored width and add it to the css
					if (!empty($col['width']) && is_numeric($col['width']) && $col['width'] > 0 ) {
						$css .= ".cp-{$type} .wrap table th.column-{$col_name} { width: {$col['width']}% !important; }";
					}
				}
			}
		}

		echo "<style type='text/css'>{$css}</style>";
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
	 * Add help tabs
	 *
	 * @since     1.3
	 */
	public function help_tabs($page)
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
	 * Plugin Settings
	 *
	 * @since     1.3.1
	 */
	private function plugin_settings()
	{
		$class_current_settings = $this->is_menu_type_current('plugin_settings') ? ' current' : ' hidden'; '';

		/** Sortable */
		$masked_key 				= '';
		$class_sortorder_activate 	= '';
		$class_sortorder_deactivate = ' hidden';

		// is unlocked
		$licence = new cpac_licence('sortable');

		if ( $licence->is_unlocked() ) {
			$masked_key 	 = $licence->get_masked_license_key('sortable');
			$class_sortorder_activate = ' hidden';
			$class_sortorder_deactivate = '';
		}

		// find out more
		$find_out_more = "<a href='{$this->codepress_url}/sortorder-addon/' class='button-primary alignright' target='_blank'>".__('find out more', CPAC_TEXTDOMAIN)." &raquo</a>";

		// info box
		$sortable_tooltip = "
			<p>".__('This will make all of the new columns support sorting', CPAC_TEXTDOMAIN).".</p>
			<p>".__('By default WordPress let\'s you sort by title, date, comments and author. This will make you be able to <strong>sort by any column of any type!</strong>', CPAC_TEXTDOMAIN)."</p>
			<p>".__('Perfect for sorting your articles, media files, comments, links and users', CPAC_TEXTDOMAIN).".</p>
			<p class='description'>".__('(columns that are added by other plugins are not supported)', CPAC_TEXTDOMAIN).".</p>
			<img src='" . CPAC_URL.'/assets/images/addon_sortable_1.png' . "' alt='' />
			{$find_out_more}
		";

		// addons
		$addons = "
			<tr>
				<td colspan='2'>
					<h2>".__('Activate Add-ons', CPAC_TEXTDOMAIN)."</h2>
					<p>".__('Add-ons can be unlocked by purchasing a license key. Each key can be used on multiple sites', CPAC_TEXTDOMAIN)." <a target='_blank' href='{$this->codepress_url}/sortorder-addon/'>Visit the Plugin Store</a>.</p>
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
											<p>" . __($sortable_tooltip, CPAC_TEXTDOMAIN) . "</p>
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

		// settings
		$row = "
		<tr id='cpac-box-plugin_settings' valign='top' class='cpac-box-row {$class_current_settings}'>
			<td colspan='2'>
				<table class='nopadding'>
					{$addons}
					{$general_options}
				</table>
			</td>
		</tr><!-- #cpac-box-plugin_settings -->
		";

		return $row;
	}

	/**
	 *	Get post count
	 *
	 * 	@since     1.3.1
	 */
	public static function get_post_count( $post_type, $user_id )
	{
		global $wpdb;

		if ( ! post_type_exists($post_type) || empty($user_id) )
			return false;

		$sql = "
			SELECT COUNT(ID)
			FROM {$wpdb->posts}
			WHERE post_status = 'publish'
			AND post_author = %d
			AND post_type = %s
		";

		return $wpdb->get_var( $wpdb->prepare($sql, $user_id, $post_type) );
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
		// loop through post types
		$rows = '';
		foreach ( $this->get_types() as $type ) {

			// post type label
			$label = $this->get_singular_name($type);

			// id
			$id = $this->sanitize_string($type);

			// build draggable boxes
			$boxes = $this->get_column_boxes($type);

			// class
			$class = $this->is_menu_type_current($type) ? ' current' : ' hidden';

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

		// General Setttings
		$general_settings = $this->plugin_settings();

		// Post Type Menu
		$menu = $this->get_menu();

		// Help screen message
		$help_text = '';
		if ( version_compare( get_bloginfo('version'), '3.2', '>' ) )
			$help_text = '<p>'.__('You will find a short overview at the <strong>Help</strong> section in the top-right screen.', CPAC_TEXTDOMAIN).'</p>';

		// find out more
		$find_out_more = "<a href='{$this->codepress_url}/sortorder-addon/' class='alignright green' target='_blank'>".__('find out more', CPAC_TEXTDOMAIN)." &raquo</a>";

	?>
		<div id="cpac" class="wrap">
			<?php screen_icon(CPAC_SLUG) ?>
			<h2><?php _e('Codepress Admin Columns', CPAC_TEXTDOMAIN); ?></h2>
			<?php echo $menu ?>

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
									<li><a href="<?php echo $this->plugins_url ?>"><?php _e('Give it a 5 star rating on WordPress.org.', CPAC_TEXTDOMAIN) ?></a></li>
									<li><a href="<?php echo $this->codepress_url ?>/"><?php _e('Link to it so other folks can find out about it.', CPAC_TEXTDOMAIN) ?></a></li>
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
								<p><?php printf(__('If you are having problems with this plugin, please talk about them in the <a href="%s">Support forums</a> or send me an email %s.', CPAC_TEXTDOMAIN), $this->wordpress_url, '<a href="mailto:info@codepress.nl">info@codepress.nl</a>' );?></p>
								<p><?php printf(__("If you're sure you've found a bug, or have a feature request, please <a href='%s'>submit your feedback</a>.", CPAC_TEXTDOMAIN), "{$this->codepress_url}/feedback");?></p>
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
									<!-- columns -->
									<?php echo $rows; ?>

									<!-- activation -->
									<?php echo $general_settings; ?>

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

/**
 * Init Class Codepress_Admin_Columns
 *
 * @since     1.0
 */
new Codepress_Admin_Columns();


/**
 * Init Class Codepress_Sortable_Columns
 *
 * @since     1.3
 */
new Codepress_Sortable_Columns();

?>