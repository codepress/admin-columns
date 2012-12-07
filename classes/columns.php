<?php

class cpac_columns
{
	private $type,
			$use_hidden_custom_fields;
	
	function __construct( $type = '' )
	{
		$this->type = $type;
		
		// enable the use of custom hidden fields
		$this->use_hidden_custom_fields = apply_filters('cpac_use_hidden_custom_fields', false);
	}
	
	/**
	 * Get a list of Column options per post type
	 *
	 * @since     1.0
	 */
	public function get_column_boxes() 
	{		
		$type = $this->type;
		
		// merge all columns
		$display_columns 	= self::get_merged_columns( $type );
		
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
				$more_options 	= $this->get_additional_box_options($this->type, $id, $values);
				$action 		= "<a class='cpac-action' href='#open'>open</a>";
						
				// type label
				$type_label = isset($values['options']['type_label']) ? $values['options']['type_label'] : '';
				
				// label
				$label = isset($values['label']) ? str_replace("'", '"', $values['label']) : '';
				
				// main label
				$main_label = $values['label'];	
				
				// main label exception for comments
				if ( 'comments' == $id ) {
					$main_label = cpac_static::get_comment_icon();
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
	function get_merged_columns() 
	{
		/** Comments */
		if ( $this->type == 'wp-comments' ) {
			$wp_default_columns = $this->get_wp_default_comments_columns();
			$wp_custom_columns  = $this->get_custom_comments_columns();
		}
		
		/** Links */
		elseif ( $this->type == 'wp-links' ) {
			$wp_default_columns = $this->get_wp_default_links_columns();
			$wp_custom_columns  = $this->get_custom_links_columns();
		}
		
		/** Users */
		elseif ( $this->type == 'wp-users' ) {
			$wp_default_columns = $this->get_wp_default_users_columns();
			$wp_custom_columns  = $this->get_custom_users_columns();
		}
		
		/** Media */
		elseif ( $this->type == 'wp-media' ) {
			$wp_default_columns = $this->get_wp_default_media_columns();
			$wp_custom_columns  = $this->get_custom_media_columns();
		}
		
		/** Posts */
		else {
			$wp_default_columns = $this->get_wp_default_posts_columns($this->type);
			$wp_custom_columns  = $this->get_custom_posts_columns($this->type);
		}
		
		// merge columns
		$default_columns = wp_parse_args($wp_custom_columns, $wp_default_columns);
		
		//get saved database columns		
		if ( $db_columns = cpac_static::get_stored_columns( $this->type ) ) {
			
			// let's remove any unavailable columns.. such as disabled plugins			
			$diff = array_diff( array_keys($db_columns), array_keys($default_columns) );
			
			// check or differences
			if ( ! empty($diff) && is_array($diff) ) {						
				foreach ( $diff as $column_name ){				
					// make an exception for column-meta-xxx
					if ( ! cpac_static::is_column_meta($column_name) ) {
						unset($db_columns[$column_name]);
					}
				}
			}	
			
			// loop throught the active columns
			foreach ( $db_columns as $id => $values ) {
			
				// get column meta options from custom columns
				if ( cpac_static::is_column_meta($id) && !empty($wp_custom_columns['column-meta-1']['options']) ) {					
					$db_columns[$id]['options'] = $wp_custom_columns['column-meta-1']['options'];			
				}
				
				// add static options
				elseif ( isset($default_columns[$id]['options']) )
					$db_columns[$id]['options'] = $default_columns[$id]['options'];
				
				unset($default_columns[$id]);			
			}
		}	
		
		// merge all
		$display_columns = wp_parse_args($db_columns, $default_columns);		
		
		return $display_columns;		
	}
	
	/**
	 * 	Get WP default links columns.
	 *
	 * 	@since     1.3.1
	 */
	function get_wp_default_comments_columns()
	{		
		// dependencies
		if ( file_exists(ABSPATH . 'wp-admin/includes/class-wp-list-table.php') )
			require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
		if ( file_exists(ABSPATH . 'wp-admin/includes/class-wp-comments-list-table.php') )
			require_once(ABSPATH . 'wp-admin/includes/class-wp-comments-list-table.php');
		
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
		
		// change to uniform format
		$columns = $this->get_uniform_format($columns);
		
		// add sorting to some of the default links columns
		if ( !empty($columns['comment']) ) {
			$columns['comment']['options']['sortorder'] = 'on';
		}
		
		return apply_filters('cpac-default-comments-columns', $columns);
	}
	
	/**
	 * Custom comments columns
	 *
	 * @since     1.3.1
	 */
	function get_custom_comments_columns() 
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
	 * Custom links columns
	 *
	 * @since     1.3.1
	 */
	function get_custom_links_columns() 
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
	 * Custom posts columns
	 *
	 * @since     1.0
	 */
	function get_custom_posts_columns($post_type) 
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
	function get_custom_users_columns() 
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
		foreach ( cpac_static::get_post_types() as $post_type ) {
			$label = '';
			
			// get plural label
			$posttype_obj = get_post_type_object( $post_type );
			if ( $posttype_obj ) {
				$label = $posttype_obj->labels->name;
			}			

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
	function get_custom_media_columns() 
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
	 * 	Get WP default supported admin columns per post type.
	 *
	 * 	@since     1.0
	 */
	function get_wp_default_posts_columns($post_type = 'post') 
	{
		// we need to change the current screen
		global $current_screen;
			
		// some plugins depend on settings the $_GET['post_type'] variable such as ALL in One SEO
		$_GET['post_type'] = $post_type;
		
		// to prevent possible warning from initializing load-edit.php 
		// we will set a dummy screen object
		if ( empty($current_screen->post_type) ) {
			$current_screen = (object) array( 'post_type' => $post_type, 'id' => '', 'base' => '' );			
		}		
		
		// for 3rd party plugin support we will call load-edit.php so all the 
		// additional columns that are set by them will be avaible for us		
		do_action('load-edit.php');
		
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
			
			// #48 - In WP Release v3.5 we can use the following.
			// $table = new WP_Posts_List_Table(array( 'screen' => $post_type ));
			// $columns = $table->get_columns();
			
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
		
		if ( empty ( $columns ) )
			return false;
			
		// change to uniform format
		$columns = $this->get_uniform_format($columns);		

		// add sorting to some of the default links columns
		
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
	function get_wp_default_users_columns()
	{
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
	function get_wp_default_media_columns()
	{		
		// @todo could use _get_list_table('WP_Media_List_Table') ?
		if ( file_exists(ABSPATH . 'wp-admin/includes/class-wp-list-table.php') )
			require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
		if ( file_exists(ABSPATH . 'wp-admin/includes/class-wp-media-list-table.php') )
			require_once(ABSPATH . 'wp-admin/includes/class-wp-media-list-table.php');
		
		// #48 - In WP Release v3.5 we can use the following.
		// $table = new WP_Media_List_Table(array( 'screen' => 'upload' ));
		// $columns = $table->get_columns();
		
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
		
		// change to uniform format
		$columns = $this->get_uniform_format($columns);
		
		return apply_filters('cpac-default-media-columns', $columns);
	}
	
	/**
	 * 	Get WP default links columns.
	 *
	 * 	@since     1.3.1
	 */
	function get_wp_default_links_columns()
	{
		// dependencies
		if ( file_exists(ABSPATH . 'wp-admin/includes/class-wp-list-table.php') )
			require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
		if ( file_exists(ABSPATH . 'wp-admin/includes/class-wp-links-list-table.php') )
			require_once(ABSPATH . 'wp-admin/includes/class-wp-links-list-table.php');
		
		// get links columns
		$columns = WP_Links_List_Table::get_columns();

		// change to uniform format
		$columns = $this->get_uniform_format($columns);
		
		// add sorting support to rel-tag
		if ( !empty($columns['rel']) ) {
			$columns['rel']['options']['sortorder'] = 'on';
		}
		
		return apply_filters('cpac-default-links-columns', $columns);
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
				if ( ! cpac_static::is_column_meta($column_name) ) {
					unset($db_columns[$column_name]);
				}
			}
		}
		
		return $db_columns;
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
		if( cpac_static::is_column_meta($id) ) {
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
			$sql = $wpdb->prepare( "SELECT DISTINCT meta_key FROM {$wpdb->postmeta} pm JOIN {$wpdb->posts} p ON pm.post_id = p.ID WHERE p.post_type = 'attachment' ORDER BY 1");
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
		
		if ( !empty($meta_fields) )
			return $meta_fields;
		
		return false;
	}
}
