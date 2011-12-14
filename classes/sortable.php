<?php
/**
 * Init Class
 *
 * @since     1.3
 */
new Codepress_Sortable_Columns();

/**
 * Coderess Sortable Columns Class
 *
 * @since     1.3
 *
 */
class Codepress_Sortable_Columns extends Codepress_Admin_Columns
{	
	private $post_types, 
			$is_unlocked, 
			$show_all_results;
	
	/**
	 * Construct
	 *
	 * @since     1.0
	 */
	function __construct()
	{	
		add_action( 'wp_loaded', array( &$this, 'init') );
	}
	
	/**
	 * Initialize plugin.
	 *
	 * @since     1.0
	 */
	public function init()
	{
		// vars
		$this->post_types 		= parent::get_post_types();
		$this->is_unlocked 		= parent::is_unlocked('sortable');
		$this->show_all_results = false;

		add_action( 'admin_init', array( &$this, 'register_sortable_columns' ) );
		// handle requests for sorting columns
		add_filter( 'request', array( &$this, 'handle_requests_orderby_column'), 1 );
		add_action( 'pre_user_query', array( &$this, 'handle_requests_orderby_users_column'), 1 );
	}
	
	function register_sortable_columns()
	{
		if ( ! $this->is_unlocked )
			return false;
			
		/** Posts */
	 	foreach ( $this->post_types as $post_type )
			add_filter( "manage_edit-{$post_type}_sortable_columns", array(&$this, 'callback_add_sortable_posts_column'));
				
		/** Users */
		add_filter( "manage_users_sortable_columns", array(&$this, 'callback_add_sortable_users_column'));
		
		/** Media */
		add_filter( "manage_upload_sortable_columns", array(&$this, 'callback_add_sortable_media_column'));
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
	 *	Add managed sortable columns by Type
	 *
	 * 	@since     1.1
	 */
	private function add_managed_sortable_columns( $type = 'post', $columns ) 
	{
		$display_columns	= parent::get_merged_columns($type);
			
		if ( ! $display_columns )
			return $columns;
		
		foreach ( $display_columns as $id => $vars ) {
			if ( isset($vars['options']['sortorder']) && $vars['options']['sortorder'] == 'on' ){			
				
				// register format
				$columns[$id] = parent::sanitize_string($vars['label']);			
			}
		}	
		return $columns;
	}
	
	/**
	 * Admin requests for orderby column
	 *
	 * @since     1.0
	 */
	public function handle_requests_orderby_column( $vars ) 
	{
		if ( ! isset( $vars['orderby'] ) )
			return $vars;
				
		/** Users */
		// You would expect to see get_orderby_users_vars(), but sorting for 
		// users is handled through a different filter. Not 'request', but 'pre_user_query'.
		// See handle_requests_orderby_users_column().
		
		/** Media */
		elseif ( $this->request_uri_is_media() )
			$vars = $this->get_orderby_media_vars($vars);
		
		/** Posts */
		elseif ( !empty($vars['post_type']) )
			$vars = $this->get_orderby_posts_vars($vars);
				
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
			return $vars;		
		
		// var
		$cusers = array();		
		switch( key($column) ) :
			
			case 'column-user_id':
				$user_query->query_vars['orderby'] = 'ID';
				break;
				
			case 'column-user_registered':
				$user_query->query_vars['orderby'] = 'registered';
				break;
			
			case 'column-nickname':
				$user_query->query_vars['orderby'] = 'nickname';
				break;
			
			case 'column-first_name':
				foreach ( $this->get_users_data() as $u )
					if ($u->first_name || $this->show_all_results )
						$cusers[$u->ID] = $this->prepare_sort_string_value($u->first_name);
				$this->set_users_query_vars( &$user_query, $cusers, SORT_REGULAR );
				break;
			
			case 'column-last_name':
				foreach ( $this->get_users_data() as $u )
					if ($u->last_name || $this->show_all_results )
						$cusers[$u->ID] = $this->prepare_sort_string_value($u->last_name);
				$this->set_users_query_vars( &$user_query, $cusers, SORT_REGULAR );
				break;
				
			case 'column-user_url':
				foreach ( $this->get_users_data() as $u )
					if ($u->user_url || $this->show_all_results )
						$cusers[$u->ID] = $this->prepare_sort_string_value($u->user_url);
				$this->set_users_query_vars( &$user_query, $cusers, SORT_REGULAR );
				break;
			
			case 'column-user_description':
				foreach ( $this->get_users_data() as $u )
					if ($u->user_description || $this->show_all_results )
						$cusers[$u->ID] = $this->prepare_sort_string_value($u->user_description);
				$this->set_users_query_vars( &$user_query, $cusers, SORT_REGULAR );
				break;
		
		endswitch;

		return $user_query;
	}	
	
	/**
	 * Set sorting vars in User Query Object
	 *
	 * @since     1.3
	 */
	private function set_users_query_vars(&$user_query, $sortusers, $sort_flags = SORT_REGULAR )
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
			$user_query->query_orderby 	= "ORDER BY FIELD ({$wpdb->prefix}users.ID,{$ids})";
		}
		
		// cleanup the vars we dont need
		$vars['order']		= '';
		$vars['orderby'] 	= '';

		$user_query->query_vars = $vars;
	}	
	
	/**
	 * Orderby Media column
	 *
	 * @since     1.3
	 */
	private function get_orderby_media_vars($vars)
	{
		// Column
		$column = $this->get_orderby_type( $vars['orderby'], 'wp-media' );		

		if ( empty($column) )
			return $vars;
		
		// var
		$cposts = array();		
		switch( key($column) ) :
		
			case 'column-mediaid' :
				$vars['orderby'] = 'ID';
				break;
			
			case 'column-width' :
				foreach ( (array) $this->get_any_posts_by_posttype('attachment') as $p ) {
					$meta 	= wp_get_attachment_metadata($p->ID);
					$width 	= !empty($meta['width']) ? $meta['width'] : 0;
					if ( $width || $this->show_all_results )
						$cposts[$p->ID] = $width;
				}
				$this->set_vars_post__in( &$vars, $cposts, SORT_NUMERIC );
				break;
				
			case 'column-height' :
				foreach ( (array) $this->get_any_posts_by_posttype('attachment') as $p ) {
					$meta 	= wp_get_attachment_metadata($p->ID);
					$height	= !empty($meta['height']) ? $meta['height'] : 0;
					if ( $height || $this->show_all_results )
						$cposts[$p->ID] = $height;
				}
				$this->set_vars_post__in( &$vars, $cposts, SORT_NUMERIC );
				break;
			
			case 'column-dimensions' :
				foreach ( (array) $this->get_any_posts_by_posttype('attachment') as $p ) {
					$meta 	 = wp_get_attachment_metadata($p->ID);
					$height	 = !empty($meta['height']) 	? $meta['height'] 	: 0;
					$width	 = !empty($meta['width']) 	? $meta['width'] 	: 0;
					$surface = $height*$width;
					
					if ( $surface || $this->show_all_results )
						$cposts[$p->ID] = $surface;
				}
				$this->set_vars_post__in( &$vars, $cposts, SORT_NUMERIC );
				break;
			
			case 'column-caption' :
				foreach ( (array) $this->get_any_posts_by_posttype('attachment') as $p )
					if ( $p->post_excerpt || $this->show_all_results )
						$cposts[$p->ID] = $this->prepare_sort_string_value($p->post_excerpt);					
				$this->set_vars_post__in( &$vars, $cposts, SORT_STRING);
				break;
				
			case 'column-description' :
				foreach ( (array) $this->get_any_posts_by_posttype('attachment') as $p )
					if ( $p->post_content || $this->show_all_results )
						$cposts[$p->ID] = $this->prepare_sort_string_value( $p->post_content );
				$this->set_vars_post__in( &$vars, $cposts, SORT_STRING);
				break;
			
			case 'column-mime_type' :
				foreach ( (array) $this->get_any_posts_by_posttype('attachment') as $p )
					if ( $p->post_mime_type || $this->show_all_results )
						$cposts[$p->ID] = $this->prepare_sort_string_value( $p->post_mime_type );		
				$this->set_vars_post__in( &$vars, $cposts, SORT_STRING);
				break;
			
			case 'column-file_name' :
				foreach ( (array) $this->get_any_posts_by_posttype('attachment') as $p ) {					
					$meta 	= get_post_meta($p->ID, '_wp_attached_file', true);					
					$file	= !empty($meta) ? basename($meta) : '';
					if ( $file || $this->show_all_results )
						$cposts[$p->ID] = $file;
				}
				$this->set_vars_post__in( &$vars, $cposts, SORT_STRING);
				break;

			case 'column-alternate_text' :
				foreach ( (array) $this->get_any_posts_by_posttype('attachment') as $p ) {
					$alt = get_post_meta($p->ID, '_wp_attachment_image_alt', true);
					if ( $alt || $this->show_all_results ) {
						$cposts[$p->ID] = $this->prepare_sort_string_value( $alt );
					}
				}
				$this->set_vars_post__in( &$vars, $cposts, SORT_STRING);
				break;			
		
		endswitch;

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
		
		// Column
		$column = $this->get_orderby_type( $vars['orderby'], $post_type );		

		if ( empty($column) )
			return $vars;
		
		// id
		$id = key($column);
		
		// type
		$type = $id;
		
		// custom fields
		if ( parent::is_column_meta($type) )
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
				foreach ( (array) $this->get_any_posts_by_posttype($post_type) as $p ) {
				
					// add excerpt to the post ids				
					$cposts[$p->ID] = $this->prepare_sort_string_value($p->post_content);
				}	
				// we will add the sorted post ids to vars['post__in'] and remove unused vars
				$this->set_vars_post__in( &$vars, $cposts, SORT_STRING );
				break;
				
			case 'column-word-count' : 
				foreach ( (array) $this->get_any_posts_by_posttype($post_type) as $p )				
					$cposts[$p->ID] = str_word_count( $this->strip_trim( $p->post_content ) );
				$this->set_vars_post__in( &$vars, $cposts, SORT_NUMERIC );
				break;
				
			case 'column-page-template' : 
				$templates 		= get_page_templates();
				foreach ( (array) $this->get_any_posts_by_posttype($post_type) as $p ) {					
					$page_template  = get_post_meta($p->ID, '_wp_page_template', true);
					$cposts[$p->ID] = array_search($page_template, $templates);
				}
				$this->set_vars_post__in( &$vars, $cposts );				
				break;
			
			case 'column-post_formats' : 
				foreach ( (array) $this->get_any_posts_by_posttype($post_type) as $p ) {					
					$cposts[$p->ID] = get_post_format($p->ID);
				}
				$this->set_vars_post__in( &$vars, $cposts );				
				break;
				
			case 'column-attachment' : 
				foreach ( (array) $this->get_any_posts_by_posttype($post_type) as $p )				
					$cposts[$p->ID] = count( parent::get_attachment_ids($p->ID) );
				$this->set_vars_post__in( &$vars, $cposts, SORT_NUMERIC );
				break;
				
				
			case 'column-page-slug' : 
				foreach ( (array) $this->get_any_posts_by_posttype($post_type) as $p )				
					$cposts[$p->ID] = $p->post_name;
				$this->set_vars_post__in( &$vars, $cposts );
				break;	
		
		endswitch;
		
		return $vars;
	}
	
	/**
	 * Set post__in for use in WP_Query
	 *
	 * This will order the ID's asc or desc and set the appropriate filters.
	 *
	 * @since     1.2.1
	 */
	private function set_vars_post__in( &$vars, $sortposts, $sort_flags = SORT_REGULAR )
	{
		// sort post ids by value
		if ( $vars['order'] == 'asc' )
			asort($sortposts, $sort_flags);
		else
			arsort($sortposts, $sort_flags);
		
		// this will make sure WP_Query will use the order of the ids that we have just set in 'post__in'
		add_filter('posts_orderby', array( &$this, 'filter_orderby_post__in'), 10, 2 );

		// cleanup the vars we dont need
		$vars['order']		= '';
		$vars['orderby'] 	= '';
		
		// add the sorted post ids to the query with the use of post__in
		$vars['post__in'] = array_keys($sortposts);
	}
	
	/**
	 * Get orderby type
	 *
	 * @since     1.1
	 */
	private function get_orderby_type($orderby, $type)
	{
		$db_columns = parent::get_stored_columns($type);

		if ( $db_columns ) {
			foreach ( $db_columns as $id => $vars ) {
			
				// check which custom column was clicked
				if ( isset( $vars['label'] ) && $orderby ==  parent::sanitize_string( $vars['label'] ) ) {
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
			return "FIELD ({$wpdb->prefix}posts.ID,{$ids})";
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
		return $allposts;		
	}
	/**
	 * Request URI is Media
	 *
	 * @since     1.3
	 */
	private function request_uri_is_media()
	{
		if (strpos( $_SERVER['REQUEST_URI'], '/upload.php' ) !== false ) 
			return true;
		
		return false;
	}
	
	/**
	 * Request URI is Users
	 *
	 * @since     1.3
	 */
	private function request_uri_is_users()
	{
		if (strpos( $_SERVER['REQUEST_URI'], '/users.php' ) !== false ) 
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
		$string = strtolower( substr( parent::strip_trim($string),0 ,20 ) );
		
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
}