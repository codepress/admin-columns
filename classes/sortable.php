<?php

/**
 * Coderess Sortable Columns Class
 *
 * @since     1.3
 *
 */
class Codepress_Sortable_Columns extends Codepress_Admin_Columns
{	
	private $post_types, 
			$unlocked, 
			$show_all_results;
	
	/**
	 * Constructor
	 *
	 * @since     1.0
	 */
	function __construct()
	{
		add_action( 'wp_loaded', array( $this, 'init') );
	}
	
	/**
	 * Initialize
	 *
	 * @since     1.0
	 */
	public function init()
	{
		// vars
		$this->unlocked 		= $this->is_unlocked('sortable');
		$this->post_types 		= Codepress_Admin_Columns::get_post_types();
		$this->show_all_results = false;
		
		// init sorting
		add_action( 'admin_init', array( $this, 'register_sortable_columns' ) );
		
		// init filtering
		add_action( 'admin_init', array( $this, 'register_filtering_columns' ) );
		
		// handle requests for sorting columns
		add_filter( 'request', array( $this, 'handle_requests_orderby_column'), 1 );
		add_action( 'pre_user_query', array( $this, 'handle_requests_orderby_users_column'), 1 );
		add_action( 'admin_init', array( $this, 'handle_requests_orderby_links_column'), 1 );
		add_action( 'admin_init', array( $this, 'handle_requests_orderby_comments_column'), 1 );
	}	
	
	/**
	 * 	Register sortable columns
	 *
	 *	Hooks into apply_filters( "manage_{$screen->id}_sortable_columns" ) which is found in class-wp-list-table.php
	 *
	 * 	@since     1.0
	 */
	function register_sortable_columns()
	{
		if ( ! $this->unlocked )
			return false;
	
		/** Posts */
	 	foreach ( $this->post_types as $post_type )
			add_filter( "manage_edit-{$post_type}_sortable_columns", array($this, 'callback_add_sortable_posts_column'));				
		
		/** Users */
		add_filter( "manage_users_sortable_columns", array($this, 'callback_add_sortable_users_column'));
		
		/** Media */
		add_filter( "manage_upload_sortable_columns", array($this, 'callback_add_sortable_media_column'));
		
		/** Links */
		add_filter( "manage_link-manager_sortable_columns", array($this, 'callback_add_sortable_links_column'));
		
		/** Comments */
		add_filter( "manage_edit-comments_sortable_columns", array($this, 'callback_add_sortable_comments_column'));
	}
	
	/**
	 *	Callback add Posts sortable column
	 *
	 * 	@since     1.0
	 */
	public function callback_add_sortable_posts_column($columns) 
	{
		global $post_type;
		
		return $this->add_managed_sortable_columns($post_type, $columns);
	}

	/**
	 *	Callback add Users sortable column
	 *
	 * 	@since     1.1
	 */
	public function callback_add_sortable_users_column($columns) 
	{
		return $this->add_managed_sortable_columns('wp-users', $columns);
	}
	
	/**
	 *	Callback add Media sortable column
	 *
	 * 	@since     1.3
	 */
	public function callback_add_sortable_media_column($columns) 
	{
		return $this->add_managed_sortable_columns('wp-media', $columns);
	}
	
	/**
	 *	Callback add Links sortable column
	 *
	 * 	@since     1.3.1
	 */
	public function callback_add_sortable_links_column($columns) 
	{
		return $this->add_managed_sortable_columns('wp-links', $columns);
	}
	
	/**
	 *	Callback add Comments sortable column
	 *
	 * 	@since     1.3.1
	 */
	public function callback_add_sortable_comments_column($columns) 
	{
		return $this->add_managed_sortable_columns('wp-comments', $columns);
	}
	
	/**
	 *	Add managed sortable columns by Type
	 *
	 * 	@since     1.1
	 */
	private function add_managed_sortable_columns( $type = 'post', $columns ) 
	{
		$display_columns	= $this->get_merged_columns($type);
		
		if ( ! $display_columns )
			return $columns;
		
		foreach ( $display_columns as $id => $vars ) {
			if ( isset($vars['options']['sortorder']) && $vars['options']['sortorder'] == 'on' ){			
				
				// register format
				$columns[$id] = $this->sanitize_string($vars['label']);			
			}
		}	

		return $columns;
	}
	
	/**
	 * Admin requests for orderby column
	 *
	 * Only works for WP_Query objects ( such as posts and media )
	 *
	 * @since     1.0
	 */
	public function handle_requests_orderby_column( $vars ) 
	{
		/** Users */
		// You would expect to see get_orderby_users_vars(), but sorting for 
		// users is handled through a different filter. Not 'request', but 'pre_user_query'.
		// See handle_requests_orderby_users_column().
						
		/** Media */
		if ( $this->request_uri_is('upload') ) {
			$vars = $this->get_orderby_media_vars($vars);
		}
		
		/** Posts */
		elseif ( !empty($vars['post_type']) ) {
			$vars = $this->get_orderby_posts_vars($vars);
		}
				
		return $vars;
	}	

	/**
	 * 	Get the default sorting of the column
	 *
	 *	The default sorting of the column is saved to it's property default_order.
	 *	We will overwrite the requested 'order' and 'orderby' variables with the default_order.
	 *
	 * 	@since     1.4.5
	 */
	function get_default_sorting_vars( $type, $vars )
	{			
		// retrieve the default_order of this type
		$db_columns = Codepress_Admin_Columns::get_stored_columns($type);
		
		if ( $db_columns ) {
			foreach ( $db_columns as $column ) {
				if ( empty($column['default_order'] ) )
					continue;
				
				// overwrite with the new defaults
				$vars['orderby'] = $this->sanitize_string($column['label']);
				$vars['order'] = $column['default_order'];				
			}
		}

		return $vars;
	}
	
	/**
	 * Orderby Users column
	 *
	 * @since     1.3
	 */
	public function handle_requests_orderby_users_column($user_query)
	{
		// query vars
		$vars = $user_query->query_vars;
		
		// Column
		$column = $this->get_orderby_type( $vars['orderby'], 'wp-users' );

		if ( empty($column) )
			return $user_query;		
		
		// id
		$type = $id = key($column);
		
		// Check for user custom fields: column-meta-[customfieldname]
		if ( Codepress_Admin_Columns::is_column_meta($type) )
			$type = 'column-user-meta';
		
		// Check for post count: column-user_postcount-[posttype]
		if ( Codepress_Admin_Columns::get_posttype_by_postcount_column($type) )
			$type = 'column-user_postcount';
		
		// var
		$cusers = array();		
		switch( $type ) :
			
			case 'column-user_id':
				$user_query->query_orderby = "ORDER BY ID {$user_query->query_vars['order']}";
				$user_query->query_vars['orderby'] = 'ID';
				break;
				
			case 'column-user_registered':				
				$user_query->query_orderby = "ORDER BY user_registered {$user_query->query_vars['order']}";
				$user_query->query_vars['orderby'] = 'registered';
				break;
			
			case 'column-nickname' :
				$sort_flag = SORT_REGULAR;
				foreach ( $this->get_users_data() as $u ) {
					if ($u->nickname || $this->show_all_results ) {
						$cusers[$u->ID] = $this->prepare_sort_string_value($u->nickname);
					}
				}				
				break;
			
			case 'column-first_name' :
				$sort_flag = SORT_REGULAR;
				foreach ( $this->get_users_data() as $u ) {
					if ($u->first_name || $this->show_all_results ) {
						$cusers[$u->ID] = $this->prepare_sort_string_value($u->first_name);
					}
				}				
				break;
			
			case 'column-last_name' :
				$sort_flag = SORT_REGULAR;
				foreach ( $this->get_users_data() as $u ) {
					if ($u->last_name || $this->show_all_results ) {
						$cusers[$u->ID] = $this->prepare_sort_string_value($u->last_name);
					}
				}				
				break;
				
			case 'column-user_url' :
				$sort_flag = SORT_REGULAR;
				foreach ( $this->get_users_data() as $u ) {
					if ($u->user_url || $this->show_all_results ) {
						$cusers[$u->ID] = $this->prepare_sort_string_value($u->user_url);
					}
				}				
				break;
			
			case 'column-user_description' :
				$sort_flag = SORT_REGULAR;
				foreach ( $this->get_users_data() as $u ) {
					if ($u->user_description || $this->show_all_results ) {
						$cusers[$u->ID] = $this->prepare_sort_string_value($u->user_description);
					}
				}				
				break;
			
			case 'column-user_postcount' :				
				$post_type 	= Codepress_Admin_Columns::get_posttype_by_postcount_column($id);
				if ( $post_type ) {
					$sort_flag = SORT_REGULAR;
					foreach ( $this->get_users_data() as $u ) {
						$count = Codepress_Admin_Columns::get_post_count( $post_type, $u->ID );
						$cusers[$u->ID] = $this->prepare_sort_string_value($count);
					}					
				}
				break;
			
			case 'column-user-meta' :	
				$field = $column[$id]['field'];
				if ( $field ) {
				
					// order numeric or string
					$sort_flag = SORT_REGULAR;
					if ( $column[$id]['field_type'] == 'numeric' || $column[$id]['field_type'] == 'library_id' ) {
						$sort_flag = SORT_NUMERIC;
					}
					
					// sort by metavalue
					foreach ( $this->get_users_data() as $u ) {
						$value = get_metadata('user', $u->ID, $field, true);
						$cusers[$u->ID] = $this->prepare_sort_string_value($value);
					}					
				}
				break;
			
			/** native WP columns */
			
			// role column
			case 'role' :
				$sort_flag = SORT_REGULAR;
				foreach ( $this->get_users_data() as $u ) {
					$role = !empty($u->roles[0]) ? $u->roles[0] : '';
					if ($role || $this->show_all_results ) {
						$cusers[$u->ID] = $this->prepare_sort_string_value($role);
					}
				}
				break;			
		
		endswitch;		
		
		// save the order you last used as the default
		// $this->save_sorting_preference( 'wp-users', $type, strtolower($vars['order']) );
		
		if ( isset($sort_flag) ) {
			$user_query = $this->get_users_query_vars( $user_query, $cusers, $sort_flag );
		}
		
		return $user_query;
	}
	
	/**
	 * 	Orderby Links column
	 *
	 *	Makes use of filter 'get_bookmarks' from bookmark.php to change the result set of the links	 
	 *
	 * 	@since     1.3.1
	 */
	public function handle_requests_orderby_links_column()
	{
		// fire only when we are in the admins link-manager
		if ( $this->request_uri_is('link-manager') )
			add_filter( 'get_bookmarks', array( $this, 'callback_requests_orderby_links_column'), 10, 2);
	}	
	
	/**
	 * 	Orderby Links column
	 *	
	 * 	@since     1.3.1
	 */
	public function callback_requests_orderby_links_column($results, $vars) 
	{	
		global $wpdb;		
		
		// Column
		$column = $this->get_orderby_type( $vars['orderby'], 'wp-links' );

		if ( empty($column) )
			return $results;		
		
		// id
		$type = $id = key($column);

		// var
		$length = '';		
		switch( $type ) :
			
			case 'column-link_id':
				if ( version_compare( get_bloginfo('version'), '3.2', '>' ) )
					$vars['orderby'] = 'link_id';
				else
					$vars['orderby'] = 'id';
				break;
				
			case 'column-owner':
				$vars['orderby'] = 'link_owner';
				break;
			
			case 'column-length':
				$vars['orderby'] = 'length';
				$length = ", CHAR_LENGTH(link_name) AS length";
				break;
			
			case 'column-target':
				$vars['orderby'] = 'link_target';
				break;
			
			case 'column-description':
				$vars['orderby'] = 'link_description';
				break;
				
			case 'column-notes':
				$vars['orderby'] = 'link_notes';
				break;
			
			case 'column-rss':
				$vars['orderby'] = 'link_rss';
				break;
			
			/** native WP columns */
			
			// Relationship
			case 'rel':				
				$vars['orderby'] = 'link_rel';
				break;
			
			default:
				$vars['orderby'] = '';			
		
		endswitch;
		
		// get bookmarks by orderby vars
		if ( $vars['orderby'] ) {
			$vars['order'] 	= mysql_escape_string($vars['order']);			
			$sql 			= "SELECT * {$length} FROM {$wpdb->links} WHERE 1=1 ORDER BY {$vars['orderby']} {$vars['order']}";	
			$results		= $wpdb->get_results($sql);
			
			// check for errors
			if( is_wp_error($results) )
				return false;
		}

		return $results;
	}
	
	/**
	 * 	Orderby Comments column
	 *	
	 * 	@since     1.3.1
	 */
	public function callback_requests_orderby_comments_column($pieces, $ref_comment) 
	{
		// get query vars		
		$vars = $ref_comment->query_vars;
		
		// Column
		$column = $this->get_orderby_type( $vars['orderby'], 'wp-comments' );

		if ( empty($column) )
			return $pieces;		
		
		// id
		$type = $id = key($column);

		// var	
		switch( $type ) :
			
			case 'column-comment_id':
				$pieces['orderby'] = 'comment_ID';
				break;
			
			case 'column-author_author':
				$pieces['orderby'] = 'comment_author';
				break;
			
			case 'column-author_ip':
				$pieces['orderby'] = 'comment_author_IP';
				break;
				
			case 'column-author_url':
				$pieces['orderby'] = 'comment_author_url';
				break;
			
			case 'column-author_email':
				$pieces['orderby'] = 'comment_author_email';
				break;
			
			case 'column-reply_to':
				break;
			
			case 'column-approved':
				$pieces['orderby'] = 'comment_approved';
				break;
			
			case 'column-date':
				$pieces['orderby'] = 'comment_date';
				break;
			
			case 'column-agent':
				$pieces['orderby'] = 'comment_agent';
				break;
			
			case 'column-excerpt':
				$pieces['orderby'] = 'comment_content';
				break;
			
			case 'column-date_gmt':
				break;
			
			/** native WP columns */
			
			// Relationship
			case 'comment':				
				$pieces['orderby'] = 'comment_content';
				break;
			
			default:
				$vars['orderby'] = '';			
		
		endswitch;

		return $pieces;
	}
	
	/**
	 * 	Orderby Comments column
	 *
	 * 	@since     1.3.1
	 */
	public function handle_requests_orderby_comments_column()
	{		
		// fire only when we are in the admins edit-comments
		if ( $this->request_uri_is('edit-comments') ) {
			add_filter('comments_clauses', array( $this, 'callback_requests_orderby_comments_column'), 10, 2);
		}
	}
	
	/**
	 * Get sorting vars in User Query Object
	 *
	 * @since     1.3
	 */
	private function get_users_query_vars( $user_query, $sortusers, $sort_flags = SORT_REGULAR )
	{	
		global $wpdb;
		
		// vars
		$vars = $user_query->query_vars;

		// sorting
		if ( $vars['order'] == 'ASC' )
			asort($sortusers, $sort_flags);
		else
			arsort($sortusers, $sort_flags);

		// alter orderby SQL		
		if ( ! empty ( $sortusers ) ) {			
			$ids = implode(',', array_keys($sortusers));
			$user_query->query_where 	.= " AND {$wpdb->prefix}users.ID IN ({$ids})";
			$user_query->query_orderby 	= "ORDER BY FIELD({$wpdb->prefix}users.ID,{$ids})";
		}
		
		// cleanup the vars we dont need
		$vars['order']		= '';
		$vars['orderby'] 	= '';
		
		// set query vars
		$user_query->query_vars = $vars;
		
		return $user_query;
	}	
	
	/**
	 * Orderby Media column
	 *
	 * @since     1.3
	 */
	private function get_orderby_media_vars($vars)
	{
		// apply default sorting when it has been set
		if ( empty( $vars['orderby'] ) ) {
			$vars = $this->get_default_sorting_vars( 'wp-media', $vars );
			
			// when sorting still isn't set we will just return the requested vars
			if ( empty( $vars['orderby'] ) )
				return $vars;
		}
		
		// Column
		$column = $this->get_orderby_type( $vars['orderby'], 'wp-media' );		

		if ( empty($column) )
			return $vars;
		
		$id = key($column);
		
		// var
		$cposts = array();		
		switch( $id ) :
		
			case 'column-mediaid' :
				$vars['orderby'] = 'ID';
				break;
			
			case 'column-width' :
				$sort_flag = SORT_NUMERIC;
				foreach ( $this->get_any_posts_by_posttype('attachment') as $p ) {
					$meta 	= wp_get_attachment_metadata($p->ID);
					$width 	= !empty($meta['width']) ? $meta['width'] : 0;
					if ( $width || $this->show_all_results )
						$cposts[$p->ID] = $width;
				}
				break;
				
			case 'column-height' :
				$sort_flag = SORT_NUMERIC;
				foreach ( $this->get_any_posts_by_posttype('attachment') as $p ) {
					$meta 	= wp_get_attachment_metadata($p->ID);
					$height	= !empty($meta['height']) ? $meta['height'] : 0;
					if ( $height || $this->show_all_results )
						$cposts[$p->ID] = $height;
				}
				break;
			
			case 'column-dimensions' :
				$sort_flag = SORT_NUMERIC;
				foreach ( $this->get_any_posts_by_posttype('attachment') as $p ) {
					$meta 	 = wp_get_attachment_metadata($p->ID);
					$height	 = !empty($meta['height']) 	? $meta['height'] 	: 0;
					$width	 = !empty($meta['width']) 	? $meta['width'] 	: 0;
					$surface = $height*$width;
					
					if ( $surface || $this->show_all_results )
						$cposts[$p->ID] = $surface;
				}
				break;
			
			case 'column-caption' :
				$sort_flag = SORT_STRING;
				foreach ( $this->get_any_posts_by_posttype('attachment') as $p ) {
					if ( $p->post_excerpt || $this->show_all_results ) {
						$cposts[$p->ID] = $this->prepare_sort_string_value($p->post_excerpt);
					}
				}
				break;
				
			case 'column-description' :
				$sort_flag = SORT_STRING;
				foreach ( $this->get_any_posts_by_posttype('attachment') as $p ) {
					if ( $p->post_content || $this->show_all_results ) {
						$cposts[$p->ID] = $this->prepare_sort_string_value( $p->post_content );
					}
				}
				break;
			
			case 'column-mime_type' :
				$sort_flag = SORT_STRING;
				foreach ( $this->get_any_posts_by_posttype('attachment') as $p ) {
					if ( $p->post_mime_type || $this->show_all_results ) {
						$cposts[$p->ID] = $this->prepare_sort_string_value( $p->post_mime_type );
					}
				}		
				break;
			
			case 'column-file_name' :
				$sort_flag = SORT_STRING;
				foreach ( $this->get_any_posts_by_posttype('attachment') as $p ) {			
					$meta 	= get_post_meta($p->ID, '_wp_attached_file', true);					
					$file	= !empty($meta) ? basename($meta) : '';
					if ( $file || $this->show_all_results ) {
						$cposts[$p->ID] = $file;
					}
				}				
				break;

			case 'column-alternate_text' :
				$sort_flag = SORT_STRING;
				foreach ( $this->get_any_posts_by_posttype('attachment') as $p ) {
					$alt = get_post_meta($p->ID, '_wp_attachment_image_alt', true);
					if ( $alt || $this->show_all_results ) {
						$cposts[$p->ID] = $this->prepare_sort_string_value( $alt );
					}
				}
				break;
				
			case 'column-filesize' :
				$sort_flag = SORT_NUMERIC;
				foreach ( $this->get_any_posts_by_posttype('attachment') as $p ) {
					$file 	= wp_get_attachment_url($p->ID);					
					if ( $file || $this->show_all_results ) {
						$abs			= str_replace( WP_CONTENT_URL, WP_CONTENT_DIR, $file);
						$cposts[$p->ID] = $this->prepare_sort_string_value( filesize($abs) );
					}
				}
				break;
		
		endswitch;
		
		// save the order you last used as the default
		$this->save_sorting_preference( 'wp-media', $id, $vars['order'] );
		
		// we will add the sorted post ids to vars['post__in'] and remove unused vars
		if ( isset($sort_flag) ) {
			$vars = $this->get_vars_post__in( $vars, $cposts, $sort_flag );
		}

		return $vars;
	}
	
	/**
	 * Orderby Posts column
	 *
	 * @since     1.3
	 */
	private function get_orderby_posts_vars($vars)
	{		
		$post_type = $vars['post_type'];
		
		// todo: fix default sorting
		// apply default sorting when it has been set
		if ( empty( $vars['orderby'] ) ) {
			$vars = $this->get_default_sorting_vars( $post_type, $vars );
			
			// when sorting still isn't set we will just return the requested vars
			if ( empty( $vars['orderby'] ) )
				return $vars;
		}
		
		// Column
		$column = $this->get_orderby_type( $vars['orderby'], $post_type );		

		if ( empty($column) )
			return $vars;
		
		// id
		$type = $id = key($column);
	
		// Check for taxonomies, such as column-taxonomy-[taxname]	
		if ( strpos($type, 'column-taxonomy-') !== false )
			$type = 'column-taxonomy';
		
		// custom fields
		if ( Codepress_Admin_Columns::is_column_meta($type) )
			$type = 'column-post-meta';
		
		// attachments
		if ( $type == 'column-attachment-count' )
			$type = 'column-attachment';
		
		// var
		$cposts = array();		
		switch( $type ) :
		
			case 'column-postid' :
				$vars['orderby'] = 'ID';
				break;
				
			case 'column-order' : 
				$vars['orderby'] = 'menu_order';
				break;
				
			case 'column-modified' : 
				$vars['orderby'] = 'modified';
				break;
			
			case 'column-comment-count' : 
				$vars['orderby'] = 'comment_count';
				break;
			
			case 'column-post-meta' : 				
				$field 		= $column[$id]['field'];				
				
				// orderby type
				$field_type = 'meta_value';
				if ( $column[$id]['field_type'] == 'numeric' || $column[$id]['field_type'] == 'library_id' )
					$field_type = 'meta_value_num';

				$vars = array_merge($vars, array(
					'meta_key' 	=> $field,
					'orderby' 	=> $field_type
				));
				break;
				
			case 'column-excerpt' :
				$sort_flag = SORT_STRING;
				foreach ( $this->get_any_posts_by_posttype($post_type) as $p ) {
					$cposts[$p->ID] = $this->prepare_sort_string_value($p->post_content);
				}				
				break;
				
			case 'column-word-count' :
				$sort_flag = SORT_NUMERIC;
				foreach ( $this->get_any_posts_by_posttype($post_type) as $p ) {		
					$cposts[$p->ID] = str_word_count( Codepress_Admin_Columns::strip_trim( $p->post_content ) );
				}
				break;
				
			case 'column-page-template' :
				$sort_flag = SORT_STRING;
				$templates 		= get_page_templates();
				foreach ( $this->get_any_posts_by_posttype($post_type) as $p ) {					
					$page_template  = get_post_meta($p->ID, '_wp_page_template', true);
					$cposts[$p->ID] = array_search($page_template, $templates);
				}				
				break;
			
			case 'column-post_formats' :
				$sort_flag = SORT_REGULAR;
				foreach ( $this->get_any_posts_by_posttype($post_type) as $p ) {					
					$cposts[$p->ID] = get_post_format($p->ID);
				}
				break;
				
			case 'column-attachment' :
				$sort_flag = SORT_NUMERIC;
				foreach ( $this->get_any_posts_by_posttype($post_type) as $p ) {
					$cposts[$p->ID] = count( Codepress_Admin_Columns::get_attachment_ids($p->ID) );
				}
				break;				
				
			case 'column-page-slug' :
				$sort_flag = SORT_REGULAR;
				foreach ( $this->get_any_posts_by_posttype($post_type) as $p ) {
					$cposts[$p->ID] = $p->post_name;
				}				
				break;
			
			case 'column-sticky' :
				$sort_flag = SORT_REGULAR;
				$stickies = get_option('sticky_posts');
				foreach ( $this->get_any_posts_by_posttype($post_type) as $p ) {
					$cposts[$p->ID] = $p->ID;
					if ( !empty($stickies) && in_array($p->ID, $stickies ) ) {
						$cposts[$p->ID] = 0;
					}
				}
				break;
			
			case 'column-featured_image' :
				$sort_flag = SORT_REGULAR;
				foreach ( $this->get_any_posts_by_posttype($post_type) as $p ) {
					$cposts[$p->ID] = $p->ID;
					$thumb = get_the_post_thumbnail($p->ID);
					if ( !empty($thumb) ) {
						$cposts[$p->ID] = 0;
					}
				}
				break;
			
			case 'column-roles' :
				$sort_flag = SORT_STRING;
				foreach ( $this->get_any_posts_by_posttype($post_type) as $p ) {
					$cposts[$p->ID] = 0;
					$userdata = get_userdata($p->post_author);
					if ( !empty($userdata->roles[0]) ) {
						$cposts[$p->ID] = $userdata->roles[0];
					}
				}
				break;
			
			case 'column-status' :
				$sort_flag = SORT_STRING;
				foreach ( $this->get_any_posts_by_posttype($post_type) as $p ) {
					$cposts[$p->ID] = $p->post_status.strtotime($p->post_date);
				}
				break;
			
			case 'column-comment-status' : 
				$sort_flag = SORT_STRING;
				foreach ( $this->get_any_posts_by_posttype($post_type) as $p ) {
					$cposts[$p->ID] = $p->comment_status;
				}
				break;
			
			case 'column-ping-status' : 
				$sort_flag = SORT_STRING;
				foreach ( $this->get_any_posts_by_posttype($post_type) as $p ) {
					$cposts[$p->ID] = $p->ping_status;
				}
				break;
			
			case 'column-taxonomy' :
				$sort_flag 	= SORT_STRING; // needed to sort
				$taxonomy 	= str_replace('column-taxonomy-', '', $id);
				$cposts 	= $this->get_posts_sorted_by_taxonomy($post_type, $taxonomy);
				break;
				
			case 'column-author-name' :
				$sort_flag  = SORT_STRING;
				$display_as = $column[$id]['display_as'];
				if( 'userid' == $display_as ) {
					$sort_flag  = SORT_NUMERIC;
				}
				foreach ( $this->get_any_posts_by_posttype($post_type) as $p ) {
					if ( !empty($p->post_author) ) {
						$name = Codepress_Admin_Columns::get_author_field_by_nametype($display_as, $p->post_author);							
						$cposts[$p->ID] = $name;
					}
				}
				break;
			
			/** native WP columns */
			
			// categories
			case 'categories' :
				$sort_flag 	= SORT_STRING; // needed to sort
				$cposts 	= $this->get_posts_sorted_by_taxonomy($post_type, 'category');
				break;
				
			// tags
			case 'tags' :
				$sort_flag 	= SORT_STRING; // needed to sort
				$cposts 	= $this->get_posts_sorted_by_taxonomy($post_type, 'post_tag');
				break;
			
		endswitch;
		
		// save the order you last used as the default
		$this->save_sorting_preference( $post_type, $type, $vars['order'] );
				
		// we will add the sorted post ids to vars['post__in'] and remove unused vars
		if ( isset($sort_flag) ) {
			$vars = $this->get_vars_post__in( $vars, $cposts, $sort_flag );
		}
		
		return $vars;
	}
	
	/**
	 * Save sorting preference
	 *
	 * after sorting we will save this sorting preference to the column item
	 * we set the default_order to either asc, desc or empty. 
	 * only ONE column item PER type can have a default_order
	 *
	 * @since     1.4.5
	 */
	function save_sorting_preference( $type, $column_type, $order = 'asc' )
	{
		$options = get_option('cpac_options');				
		if ( isset($options['columns'][$type][$column_type]) ) {
			
			// remove the old default_order
			foreach ($options['columns'][$type] as $k => $v) {
				if ( isset( $options['columns'][$type][$k]['default_order'] ) ) {
					unset($options['columns'][$type][$k]['default_order']);
				}
			}
			
			// set the new default order
			$options['columns'][$type][$column_type]['default_order'] = $order;
			
			// save to DB
			update_option('cpac_options', $options);
		}
	}
	
	/**
	 * Get posts sorted by taxonomy
	 *
	 * This will post ID's by the first term in the taxonomy
	 *
	 * @since     1.4.5
	 */
	function get_posts_sorted_by_taxonomy($post_type, $taxonomy = 'category') 
	{
		$cposts 	= array();
		foreach ( $this->get_any_posts_by_posttype($post_type) as $p ) {
			$cposts[$p->ID] = '';						
			$terms = get_the_terms($p->ID, $taxonomy);
			if ( !is_wp_error($terms) && !empty($terms) ) {
				// only use the first term to sort
				$term = array_shift(array_values($terms));
				if ( isset($term->term_id) ) {
					$cposts[$p->ID] = sanitize_term_field('name', $term->name, $term->term_id, $term->taxonomy, 'display');
				}						
			}
		}
		return $cposts;
	}
	
	/**
	 * Set post__in for use in WP_Query
	 *
	 * This will order the ID's asc or desc and set the appropriate filters.
	 *
	 * @since     1.2.1
	 */
	private function get_vars_post__in( &$vars, $sortposts, $sort_flags = SORT_REGULAR )
	{
		// sort post ids by value
		if ( $vars['order'] == 'asc' )
			asort($sortposts, $sort_flags);
		else
			arsort($sortposts, $sort_flags);

		// this will make sure WP_Query will use the order of the ids that we have just set in 'post__in'
		// set priority higher then default to prevent conflicts with 3rd party plugins
		add_filter('posts_orderby', array( $this, 'filter_orderby_post__in'), 10, 2 );

		// cleanup the vars we dont need
		$vars['order']		= '';
		$vars['orderby'] 	= '';
		
		// add the sorted post ids to the query with the use of post__in
		$vars['post__in'] = array_keys($sortposts);
		
		return $vars;
	}
	
	/**
	 * Get orderby type
	 *
	 * @since     1.1
	 */
	private function get_orderby_type($orderby, $type)
	{
		$db_columns = Codepress_Admin_Columns::get_stored_columns($type);

		if ( $db_columns ) {
			foreach ( $db_columns as $id => $vars ) {
			
				// check which custom column was clicked
				if ( isset( $vars['label'] ) && $orderby ==  $this->sanitize_string( $vars['label'] ) ) {
					$column[$id] = $vars;
					return $column;
				}
			}
		}
		return false;
	}
	
	/**
	 * Maintain order of ids that are set in the post__in var. 
	 *
	 * This will force the returned posts to use the order of the ID's that 
	 * have been set in post__in. Without this the ID's will be set in numeric order.
	 * See the WP_Query object for more info about the use of post__in.
	 *
	 * @since     1.2.1
	 */
	public function filter_orderby_post__in($orderby, $wp) 
	{
		global $wpdb;

		// we need the query vars
		$vars = $wp->query_vars;	
		if ( ! empty ( $vars['post__in'] ) ) {			
			// now we can get the ids
			$ids = implode(',', $vars['post__in']);
			
			// by adding FIELD to the SQL query we are forcing the order of the ID's
			return "FIELD({$wpdb->prefix}posts.ID,{$ids})";
		}
	}
	
	/**
	 * Get any posts by post_type
	 *
	 * @since     1.2.1
	 */
	private function get_any_posts_by_posttype( $post_type )
	{
		$allposts = get_posts(array(
			'numberposts'	=> -1,
			'post_status'	=> 'any',
			'post_type'		=> $post_type
		));
		return (array) $allposts;		
	}
	
	/**
	 * Request URI is
	 *
	 * @since     1.3.1
	 */
	private function request_uri_is( $screen_id = '' )
	{
		if (strpos( $_SERVER['REQUEST_URI'], "/{$screen_id}.php" ) !== false ) 
			return true;
		
		return false;
	}
	
	/**
	 * Prepare the value for being by sorting
	 *
	 * @since     1.3
	 */
	private function prepare_sort_string_value($string)
	{
		// remove tags and only get the first 20 chars and force lowercase.
		$string = strtolower( substr( Codepress_Admin_Columns::strip_trim($string),0 ,20 ) );
		
		return $string;
	}
	
	/**
	 * Get users data
	 *
	 * @since     1.3
	 */
	function get_users_data() 
	{
		$userdatas = array();
		$wp_users = get_users( array(
			'blog_id' => $GLOBALS['blog_id'],		
		));
		foreach ( $wp_users as $u ) {
			$userdatas[$u->ID] = get_userdata($u->ID);
		}
		return $userdatas;
	}
	
	/**
	 * 	Register filtering columns
	 *
	 * 	@since     1.4.2
	 */
	function register_filtering_columns()
	{
		if ( ! $this->unlocked || apply_filters( 'cpac-remove-filtering-columns', true ) )
			return false;
		
		// hook into wordpress
		add_action('restrict_manage_posts', array($this, 'callback_restrict_posts'));
	}
	
	/**
	 * 	Add taxonomy filters to posts
	 *
	 * 	@since     1.4.2
	 */
	function callback_restrict_posts()
	{
		global $post_type_object;
		
		if ( !isset($post_type_object->name) )
			return false;

		// make a filter foreach taxonomy
		$taxonomies = get_object_taxonomies($post_type_object->name, 'names');
		
		// get stored columns
		$db_columns = Codepress_Admin_Columns::get_stored_columns($post_type_object->name);

		if ( $taxonomies ) {
			foreach ( $taxonomies as $tax ) {
			
				// ignore core taxonomies
				if ( in_array($tax, array('post_tag','category','post_format') ) ) {
					continue;
				}
				
				// only display taxonomy that is active as a column
				if ( isset($db_columns['column-taxonomy-'.$tax]) && $db_columns['column-taxonomy-'.$tax]['state'] == 'on' ) {
					
					$terms = get_terms($tax);
					$terms = $this->indent($terms, 0, 'parent', 'term_id');
					$terms = $this->apply_dropdown_markup($terms);
					 
					$select = "<option value=''>".__('Show all ', CPAC_TEXTDOMAIN)."{$tax}</option>";
					if (!empty($terms)) {
						foreach( $terms as $term_slug => $term) {
							
							$selected = isset($_GET[$tax]) && $term_slug == $_GET[$tax] ? " selected='selected'" : '';
							$select .= "<option value='{$term_slug}'{$selected}>{$term}</option>";
						}
						echo "<select class='postform' name='{$tax}'>{$select}</select>";
					}					
				}
			}
		}
	}
	
	/**
	 *	Applies dropdown markup for taxonomy dropdown
	 *
	 *  @since     1.4.2
	 */
	private function apply_dropdown_markup($array, $level = 0, $output = array())
    {                
        foreach($array as $v) {
            
            $prefix = '';        
            for($i=0; $i<$level; $i++) {
                $prefix .= '&nbsp;&nbsp;';  
            }

            $output[$v->slug] = $prefix . $v->name;
            
            if ( !empty($v->children) ) {
                $output = $this->apply_dropdown_markup($v->children, ($level + 1), $output);
            }
        }
        
        return $output;
    }
	
	/**
	 * Indents any object as long as it has a unique id and that of its parent.
	 *
	 * @since     1.4.2
	 */
	private function indent($array, $parentId = 0, $parentKey = 'post_parent', $selfKey = 'ID', $childrenKey = 'children') 
    {
		$indent = array();
        
        // clean counter
        $i = 0;
        
		foreach($array as $v) {

			if ($v->$parentKey == $parentId) {
				$indent[$i] = $v;
				$indent[$i]->$childrenKey = $this->indent($array, $v->$selfKey, $parentKey, $selfKey);
                
                $i++;
			}
		}

		return $indent;
	}
}

?>