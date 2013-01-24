<?php

/**
 * CPAC_Sortable_Columns Class
 *
 * @since 1.3.0
 */
class CPAC_Sortable_Columns {

	/**
	 * Show all results when sorting
	 *
	 * By default only fields that contain data will be sorted. If you want
	 * to show empty records set this variable to return bool true.
	 *
	 * @since 1.0.0
	 *
	 * @var bool
	 */
	private $show_all_results;

	/**
	 * Current User ID
	 *
	 * @since 1.0.0
	 *
	 * @var int
	 */
	private $current_user_id;

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	function __construct() {
		add_action( 'wp_loaded', array( $this, 'init') );
	}

	/**
	 * Initialize
	 *
	 * @since 1.0.0
	 */
	public function init() {

		$this->show_all_results = apply_filters( 'cpac_show_all_results', '__return_false');
		$this->current_user_id  = get_current_user_id();

		// init sorting
		add_action( 'admin_init', array( $this, 'register_sortable_columns' ) );

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
	 * 	@since 1.0.0
	 */
	function register_sortable_columns() {
		$licence = new CPAC_Licence( 'sortable' );

		if ( ! $licence->is_unlocked() )
			return false;

		/** Posts */
	 	foreach ( CPAC_Utility::get_post_types() as $post_type ) {
			add_filter( "manage_edit-{$post_type}_sortable_columns", array($this, 'callback_add_sortable_posts_column'));
		}

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
	 * Callback add Posts sortable column
	 *
	 * @since 1.0.0
	 *
	 * @param array $columns
	 * @return array Sortable Columns
	 */
	public function callback_add_sortable_posts_column( $columns ) {
		global $post_type;

		// in some cases post_type is an array ( when clicking a tag inside the overview screen icm CCTM )
		// then we use this as a fallback so we get a string
		if ( is_array($post_type) ) {
			$post_type = $_REQUEST['post_type'];
		}

		$type = new CPAC_Columns_Posttype( $post_type );

		return array_merge( $columns, $type->get_sortable_columns() );
	}

	/**
	 * Callback add Users sortable column
	 *
	 * @since 1.1.0
	 *
	 * @param array $columns
	 * @return array Sortable Columns
	 */
	public function callback_add_sortable_users_column( $columns ) {
		$type = new CPAC_Columns_Users();

		return array_merge( $columns, $type->get_sortable_columns() );
	}

	/**
	 * Callback add Media sortable column
	 *
	 * @since 1.3.0
	 *
	 * @param array $columns
	 * @return array Sortable Columns
	 */
	public function callback_add_sortable_media_column( $columns ) {
		$type = new CPAC_Columns_Media();

		return array_merge( $columns, $type->get_sortable_columns() );
	}

	/**
	 * Callback add Links sortable column
	 *
	 * @since 1.3.1
	 *
	 * @param array $columns
	 * @return array Sortable Columns
	 */
	public function callback_add_sortable_links_column( $columns ) {
		$type = new CPAC_Columns_Links();

		return array_merge( $columns, $type->get_sortable_columns() );
	}

	/**
	 * Callback add Comments sortable column
	 *
	 * @since 1.3.1
	 *
	 * @param array $columns
	 * @return array Sortable Columns
	 */
	public function callback_add_sortable_comments_column( $columns ) {
		$type = new CPAC_Columns_Comments();

		return array_merge( $columns, $type->get_sortable_columns() );
	}

	/**
	 * Admin requests for orderby column
	 *
	 * Only works for WP_Query objects ( such as posts and media )
	 *
	 * @since 1.0.0
	 *
	 * @param array $vars
	 * @return array Vars
	 */
	public function handle_requests_orderby_column( $vars ) {
		/** Users */
		// You would expect to see get_orderby_users_vars(), but sorting for
		// users is handled through a different filter. Not 'request', but 'pre_user_query'.
		// See handle_requests_orderby_users_column().

		/** Media */
		if ( $this->request_uri_is( 'upload' ) ) {
			$vars = $this->get_orderby_media_vars( $vars );
		}

		/** Posts */
		elseif ( ! empty( $vars['post_type'] ) ) {
			$vars = $this->get_orderby_posts_vars( $vars );
		}

		return $vars;
	}

	/**
	 * Orderby Users column
	 *
	 * @since 1.3.0
	 *
	 * @param object $user_query
	 * @return array User Query
	 */
	public function handle_requests_orderby_users_column( $user_query ) {
		// query vars
		$vars = $user_query->query_vars;

		// Column
		$column = $this->get_orderby_type( $vars['orderby'], 'wp-users' );

		if ( empty( $column ) )
			return $user_query;

		$column_name		= key( $column );
		$column_name_type	= CPAC_Utility::get_column_name_type( $column_name );

		// Check for post count: column-user_postcount-[posttype]
		if ( CPAC_Utility::get_posttype_by_postcount_column( $column_name ) )
			$column_name_type = 'column-user_postcount';

		// var
		$cusers = array();
		switch ( $column_name_type ) :

			case 'column-user_id' :
				$user_query->query_orderby = "ORDER BY ID {$user_query->query_vars['order']}";
				$user_query->query_vars['orderby'] = 'ID';
				break;

			case 'column-user_registered' :
				$user_query->query_orderby = "ORDER BY user_registered {$user_query->query_vars['order']}";
				$user_query->query_vars['orderby'] = 'registered';
				break;

			case 'column-nickname' :
				$sort_flag = SORT_REGULAR;
				foreach ( $this->get_users_data() as $u ) {
					if ($u->nickname || $this->show_all_results ) {
						$cusers[$u->ID] = $this->prepare_sort_string_value( $u->nickname );
					}
				}
				break;

			case 'column-first_name' :
				$sort_flag = SORT_REGULAR;
				foreach ( $this->get_users_data() as $u ) {
					if ( $u->first_name || $this->show_all_results ) {
						$cusers[$u->ID] = $this->prepare_sort_string_value( $u->first_name );
					}
				}
				break;

			case 'column-last_name' :
				$sort_flag = SORT_REGULAR;
				foreach ( $this->get_users_data() as $u ) {
					if ( $u->last_name || $this->show_all_results ) {
						$cusers[$u->ID] = $this->prepare_sort_string_value( $u->last_name );
					}
				}
				break;

			case 'column-user_url' :
				$sort_flag = SORT_REGULAR;
				foreach ( $this->get_users_data() as $u ) {
					if ( $u->user_url || $this->show_all_results ) {
						$cusers[$u->ID] = $this->prepare_sort_string_value( $u->user_url );
					}
				}
				break;

			case 'column-user_description' :
				$sort_flag = SORT_REGULAR;
				foreach ( $this->get_users_data() as $u ) {
					if ( $u->user_description || $this->show_all_results ) {
						$cusers[$u->ID] = $this->prepare_sort_string_value( $u->user_description );
					}
				}
				break;

			case 'column-user_commentcount' :
				$sort_flag = SORT_REGULAR;
				foreach ( $this->get_users_data() as $u ) {
					$count = get_comments( array(
						'user_id'	=> $u->ID,
						'count'		=> true
					));
					$cusers[$u->ID] = $this->prepare_sort_string_value( $count );
				}
				break;

			case 'column-user_postcount' :
				$post_type 	= CPAC_Utility::get_posttype_by_postcount_column( $column_name );
				if ( $post_type ) {
					$sort_flag = SORT_REGULAR;
					foreach ( $this->get_users_data() as $u ) {
						$count = CPAC_Utility::get_post_count( $post_type, $u->ID );
						$cusers[$u->ID] = $this->prepare_sort_string_value( $count );
					}
				}
				break;

			case 'column-meta' :
				$field = $column[$column_name]['field'];
				if ( $field ) {

					// order numeric or string
					$sort_flag = SORT_REGULAR;
					if ( in_array( $column[$column_name]['field_type'], array( 'numeric', 'library_id' ) ) ) {
						$sort_flag = SORT_NUMERIC;
					}

					// sort by metavalue
					foreach ( $this->get_users_data() as $u ) {
						$value = get_metadata( 'user', $u->ID, $field, true );
						$cusers[$u->ID] = $this->prepare_sort_string_value( $value );
					}
				}
				break;

			/** native WP columns */

			// role column
			case 'role' :
				$sort_flag = SORT_REGULAR;
				foreach ( $this->get_users_data() as $u ) {
					$role = ! empty( $u->roles[0] ) ? $u->roles[0] : '';
					if ($role || $this->show_all_results ) {
						$cusers[$u->ID] = $this->prepare_sort_string_value( $role );
					}
				}
				break;

		endswitch;

		if ( isset( $sort_flag ) ) {
			// sorting
			if ( $user_query->query_vars['order'] == 'ASC' )
				asort( $cusers, $sort_flag );
			else
				arsort( $cusers, $sort_flag );

			// alter orderby SQL
			global $wpdb;
			if ( ! empty( $cusers ) ) {
				$column_names = implode( ',', array_keys( $cusers ) );
				$user_query->query_where 	.= " AND {$wpdb->prefix}users.ID IN ({$column_names})";
				$user_query->query_orderby 	= "ORDER BY FIELD({$wpdb->prefix}users.ID,{$column_names})";
			}

			// cleanup the vars we dont need
			$user_query->query_vars['order']	= '';
			$user_query->query_vars['orderby'] 	= '';
		}

		return $user_query;
	}

	/**
	 * Orderby Links column
	 *
	 * Makes use of filter 'get_bookmarks' from bookmark.php to change the result set of the links
	 *
	 * @since 1.3.1
	 */
	public function handle_requests_orderby_links_column() {
		global $pagenow;

		// fire only when we are in the admins link-manager
		if ( 'link-manager.php ' == $pagenow ) {
			add_filter( 'get_bookmarks', array( $this, 'callback_requests_orderby_links_column' ), 10, 2);
		}
	}

	/**
	 * Orderby Links column
	 *
	 * @since 1.3.1
	 *
	 * @param string $results
	 * @param array $vars
	 * @return array SQL Results
	 */
	public function callback_requests_orderby_links_column( $results, $vars ) {
		global $wpdb;

		// apply sorting preference
		$this->apply_sorting_preference( $vars, 'wp-links' );

		// Column
		$column = $this->get_orderby_type( $vars['orderby'], 'wp-links' );

		if ( empty($column) )
			return $results;

		// var
		$length = '';

		switch ( key( $column ) ) :

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
			$vars['order'] 	= mysql_escape_string( $vars['order'] );
			$sql 			= "SELECT * {$length} FROM {$wpdb->links} WHERE 1=1 ORDER BY {$vars['orderby']} {$vars['order']}";
			$bookmarks		= $wpdb->get_results( $sql );

			// check for errors
			if ( ! is_wp_error( $bookmarks ) ) {
				$results = $bookmarks;

			}
		}

		return $results;
	}

	/**
	 * Orderby Comments column
	 *
	 * @since 1.3.1
	 *
	 * @param array $pieces
	 * @param object $ref_comment Comment Query vars
	 * @return array Pieces.
	 */
	public function callback_requests_orderby_comments_column( $pieces, $ref_comment ) {

		$column = $this->get_orderby_type( $ref_comment->query_vars['orderby'], 'wp-comments' );

		if ( empty( $column ) )
			return $pieces;

		switch ( key( $column ) ) :

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

		endswitch;

		return $pieces;
	}

	/**
	 * Orderby Comments column
	 *
	 * @since 1.3.1
	 */
	public function handle_requests_orderby_comments_column() {
		global $pagenow;

		// fire only when we are in the admins edit-comments
		if ( 'edit-comments.php' == $pagenow ) {
			add_filter('comments_clauses', array( $this, 'callback_requests_orderby_comments_column'), 10, 2);
		}
	}

	/**
	 * Orderby Media column
	 *
	 * @since 1.3.0
	 *
	 * @param array $vars Media Query Variables
	 * @return array Media Query Variables
	 */
	private function get_orderby_media_vars( $vars ) {
		// apply sorting preference
		$this->apply_sorting_preference( $vars, 'wp-media' );

		// when sorting still isn't set we will just return the requested vars
		if ( empty( $vars['orderby'] ) )
			return $vars;

		// Column
		$column = $this->get_orderby_type( $vars['orderby'], 'wp-media' );

		if ( empty( $column ) )
			return $vars;

		// unsorted Attachment Posts
		$cposts = array();

		switch ( key( $column ) ) :

			case 'column-mediaid' :
				$vars['orderby'] = 'ID';
				break;

			case 'column-width' :
				$sort_flag = SORT_NUMERIC;
				foreach ( $this->get_any_posts_by_posttype( 'attachment' ) as $p ) {
					$meta 	= wp_get_attachment_metadata( $p->ID );
					$width 	= !empty($meta['width']) ? $meta['width'] : 0;
					if ( $width || $this->show_all_results )
						$cposts[$p->ID] = $width;
				}
				break;

			case 'column-height' :
				$sort_flag = SORT_NUMERIC;
				foreach ( $this->get_any_posts_by_posttype( 'attachment' ) as $p ) {
					$meta 	= wp_get_attachment_metadata( $p->ID );
					$height	= !empty($meta['height']) ? $meta['height'] : 0;
					if ( $height || $this->show_all_results )
						$cposts[$p->ID] = $height;
				}
				break;

			case 'column-dimensions' :
				$sort_flag = SORT_NUMERIC;
				foreach ( $this->get_any_posts_by_posttype( 'attachment' ) as $p ) {
					$meta 	 = wp_get_attachment_metadata( $p->ID );
					$height	 = !empty($meta['height']) 	? $meta['height'] 	: 0;
					$width	 = !empty($meta['width']) 	? $meta['width'] 	: 0;
					$surface = $height*$width;

					if ( $surface || $this->show_all_results )
						$cposts[$p->ID] = $surface;
				}
				break;

			case 'column-caption' :
				$sort_flag = SORT_STRING;
				foreach ( $this->get_any_posts_by_posttype( 'attachment' ) as $p ) {
					if ( $p->post_excerpt || $this->show_all_results ) {
						$cposts[$p->ID] = $this->prepare_sort_string_value( $p->post_excerpt );
					}
				}
				break;

			case 'column-description' :
				$sort_flag = SORT_STRING;
				foreach ( $this->get_any_posts_by_posttype( 'attachment' ) as $p ) {
					if ( $p->post_content || $this->show_all_results ) {
						$cposts[$p->ID] = $this->prepare_sort_string_value( $p->post_content );
					}
				}
				break;

			case 'column-mime_type' :
				$sort_flag = SORT_STRING;
				foreach ( $this->get_any_posts_by_posttype( 'attachment' ) as $p ) {
					if ( $p->post_mime_type || $this->show_all_results ) {
						$cposts[$p->ID] = $this->prepare_sort_string_value( $p->post_mime_type );
					}
				}
				break;

			case 'column-file_name' :
				$sort_flag = SORT_STRING;
				foreach ( $this->get_any_posts_by_posttype( 'attachment' ) as $p ) {
					$meta 	= get_post_meta( $p->ID, '_wp_attached_file', true );
					$file	= !empty($meta) ? basename($meta) : '';
					if ( $file || $this->show_all_results ) {
						$cposts[$p->ID] = $file;
					}
				}
				break;

			case 'column-alternate_text' :
				$sort_flag = SORT_STRING;
				foreach ( $this->get_any_posts_by_posttype( 'attachment' ) as $p ) {
					$alt = get_post_meta( $p->ID, '_wp_attachment_image_alt', true );
					if ( $alt || $this->show_all_results ) {
						$cposts[$p->ID] = $this->prepare_sort_string_value( $alt );
					}
				}
				break;

			case 'column-filesize' :
				$sort_flag = SORT_NUMERIC;
				foreach ( $this->get_any_posts_by_posttype( 'attachment' ) as $p ) {
					$file = wp_get_attachment_url( $p->ID );
					if ( $file || $this->show_all_results ) {
						$abs			= str_replace( WP_CONTENT_URL, WP_CONTENT_DIR, $file );
						$cposts[$p->ID] = $this->prepare_sort_string_value( filesize($abs) );
					}
				}
				break;

		endswitch;

		// we will add the sorted post ids to vars['post__in'] and remove unused vars
		if ( isset( $sort_flag ) ) {
			$vars = $this->get_vars_post__in( $vars, $cposts, $sort_flag );
		}

		return $vars;
	}

	/**
	 * Orderby Posts column
	 *
	 * @since 1.3.0
	 *
	 * @param array $vars Posts Query Vars
	 * @return array Posts Query Vars
	 */
	private function get_orderby_posts_vars( $vars ) {
		$post_type = $vars['post_type'];

		// apply sorting preference
		$this->apply_sorting_preference( $vars, $post_type );

		// no sorting
		if ( empty( $vars['orderby'] ) ) {
			return $vars;
		}

		// Column
		$column = $this->get_orderby_type( $vars['orderby'], $post_type );

		if ( empty( $column ) )
			return $vars;

		// column_name
		$column_name		= key( $column );
		$column_name_type	= CPAC_Utility::get_column_name_type( key( $column ) );

		// unsorted Posts
		$cposts = array();

		switch ( $column_name_type ) :

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

			case 'column-meta' :
				$field = $column[$column_name]['field'];

				// orderby type
				$field_type = 'meta_value';
				if ( in_array( $column[$column_name]['field_type'], array( 'numeric', 'library_id') ) )
					$field_type = 'meta_value_num';

				$vars = array_merge($vars, array(
					'meta_key' 	=> $field,
					'orderby' 	=> $field_type
				));
				break;

			case 'column-excerpt' :
				$sort_flag = SORT_STRING;
				foreach ( $this->get_any_posts_by_posttype( $post_type ) as $p ) {
					$cposts[$p->ID] = $this->prepare_sort_string_value( $p->post_content );
				}
				break;

			case 'column-word-count' :
				$sort_flag = SORT_NUMERIC;
				foreach ( $this->get_any_posts_by_posttype( $post_type ) as $p ) {
					$cposts[$p->ID] = str_word_count( CPAC_Utility::strip_trim( $p->post_content ) );
				}
				break;

			case 'column-page-template' :
				$sort_flag = SORT_STRING;
				$templates = get_page_templates();
				foreach ( $this->get_any_posts_by_posttype( $post_type ) as $p ) {
					$page_template  = get_post_meta( $p->ID, '_wp_page_template', true );
					$cposts[$p->ID] = array_search( $page_template, $templates );
				}
				break;

			case 'column-post_formats' :
				$sort_flag = SORT_REGULAR;
				foreach ( $this->get_any_posts_by_posttype( $post_type ) as $p ) {
					$cposts[$p->ID] = get_post_format( $p->ID );
				}
				break;

			case 'column-attachment' :
			case 'column-attachment-count' :
				$sort_flag = SORT_NUMERIC;
				foreach ( $this->get_any_posts_by_posttype( $post_type ) as $p ) {
					$cposts[$p->ID] = count( CPAC_Utility::get_attachment_ids( $p->ID ) );
				}
				break;

			case 'column-page-slug' :
				$sort_flag = SORT_REGULAR;
				foreach ( $this->get_any_posts_by_posttype( $post_type ) as $p ) {
					$cposts[$p->ID] = $p->post_name;
				}
				break;

			case 'column-sticky' :
				$sort_flag = SORT_REGULAR;
				$stickies = get_option( 'sticky_posts' );
				foreach ( $this->get_any_posts_by_posttype( $post_type ) as $p ) {
					$cposts[$p->ID] = $p->ID;
					if ( ! empty( $stickies ) && in_array( $p->ID, $stickies ) ) {
						$cposts[$p->ID] = 0;
					}
				}
				break;

			case 'column-featured_image' :
				$sort_flag = SORT_REGULAR;
				foreach ( $this->get_any_posts_by_posttype( $post_type ) as $p ) {
					$cposts[$p->ID] = $p->ID;
					$thumb = get_the_post_thumbnail( $p->ID );
					if ( ! empty( $thumb ) ) {
						$cposts[$p->ID] = 0;
					}
				}

				$vars['orderby'] = 'post_in';
				break;

			case 'column-roles' :
				$sort_flag = SORT_STRING;
				foreach ( $this->get_any_posts_by_posttype( $post_type ) as $p ) {
					$cposts[$p->ID] = 0;
					$userdata = get_userdata( $p->post_author );
					if ( ! empty( $userdata->roles[0] ) ) {
						$cposts[$p->ID] = $userdata->roles[0];
					}
				}
				break;

			case 'column-status' :
				$sort_flag = SORT_STRING;
				foreach ( $this->get_any_posts_by_posttype( $post_type ) as $p ) {
					$cposts[$p->ID] = $p->post_status.strtotime( $p->post_date );
				}
				break;

			case 'column-comment-status' :
				$sort_flag = SORT_STRING;
				foreach ( $this->get_any_posts_by_posttype( $post_type ) as $p ) {
					$cposts[$p->ID] = $p->comment_status;
				}
				break;

			case 'column-ping-status' :
				$sort_flag = SORT_STRING;
				foreach ( $this->get_any_posts_by_posttype( $post_type ) as $p ) {
					$cposts[$p->ID] = $p->ping_status;
				}
				break;

			case 'column-taxonomy' :
				$sort_flag 	= SORT_STRING; // needed to sort
				$taxonomy 	= CPAC_Utility::get_taxonomy_by_column_name( $column_name );
				$cposts 	= $this->get_posts_sorted_by_taxonomy( $post_type, $taxonomy );
				break;

			case 'column-author-name' :
				$sort_flag  = SORT_STRING;
				$display_as = $column[$column_name]['display_as'];
				if ( 'userid' == $display_as ) {
					$sort_flag  = SORT_NUMERIC;
				}
				foreach ( $this->get_any_posts_by_posttype( $post_type ) as $p ) {
					if ( ! empty( $p->post_author ) ) {
						$name = CPAC_Utility::get_author_field_by_nametype( $display_as, $p->post_author );
						$cposts[$p->ID] = $name;
					}
				}
				break;

			case 'column-before-moretag' :
				$sort_flag = SORT_STRING;
				foreach ( $this->get_any_posts_by_posttype( $post_type ) as $p ) {
					$extended 	= get_extended( $p->post_content );
					$content  	= ! empty( $extended['extended'] ) ? $extended['main'] : '';
					$cposts[$p->ID] = $this->prepare_sort_string_value( $content );
				}
				break;

			/** native WP columns */

			// categories
			case 'categories' :
				$sort_flag 	= SORT_STRING; // needed to sort
				$cposts 	= $this->get_posts_sorted_by_taxonomy( $post_type, 'category' );
				break;

			// tags
			case 'tags' :
				$sort_flag 	= SORT_STRING; // needed to sort
				$cposts 	= $this->get_posts_sorted_by_taxonomy( $post_type, 'post_tag' );
				break;

			// custom taxonomies
			// see: case 'column-taxonomy'

		endswitch;

		// we will add the sorted post ids to vars['post__in'] and remove unused vars
		if ( isset( $sort_flag ) ) {
			$vars = $this->get_vars_post__in( $vars, $cposts, $sort_flag );
		}

		return $vars;
	}

	/**
	 * Set sorting preference
	 *
	 * after sorting we will save this sorting preference to the column item
	 * we set the default_order to either asc, desc or empty.
	 * only ONE column item PER type can have a default_order
	 *
	 * @since 1.4.6.5
	 *
	 * @param string $type
	 * @param string $orderby
	 * @param string $order asc|desc
	 */
	function set_sorting_preference( $type, $orderby = '', $order = 'asc' ) {
		if ( !$orderby )
			return false;

		$options = get_user_meta( $this->current_user_id, 'cpac_sorting_preference', true );

		$options[$type] = array(
			'orderby'	=> $orderby,
			'order'		=> $order
		);

		update_user_meta( $this->current_user_id, 'cpac_sorting_preference', $options );
	}

	/**
	 * Get sorting preference
	 *
	 * The default sorting of the column is saved to it's property default_order.
	 * Returns the orderby and order value of that column.
	 *
	 * @since 1.4.6.5
	 *
	 * @param string $type
	 * @return array Sorting Preferences for this type
	 */
	function get_sorting_preference( $type )
	{
		$options = get_user_meta( $this->current_user_id, 'cpac_sorting_preference', true );

		if ( empty($options[$type]) )
			return false;

		return $options[$type];
	}

	/**
	 * Apply sorting preference
	 *
	 * @since 1.4.6.5
	 *
	 * @param array &$vars
	 * @param string $type
	 */
	function apply_sorting_preference( &$vars, $type ) {
		// user has not sorted
		if ( empty( $vars['orderby'] ) ) {

			// did the user sorted this column some other time?
			if ( $preference = $this->get_sorting_preference( $type ) ) {
				$vars['orderby'] 	= $preference['orderby'];
				$vars['order'] 		= $preference['order'];

				// used by active state in column header
				$_GET['orderby'] = $preference['orderby'];
				$_GET['order']	 = $preference['order'];
			}
		}

		// save the order preference
		if ( ! empty( $vars['orderby'] ) ) {
			$this->set_sorting_preference( $type, $vars['orderby'], $vars['order'] );
		}
	}

	/**
	 * Get posts sorted by taxonomy
	 *
	 * This will post ID's by the first term in the taxonomy
	 *
	 * @since 1.4.5
	 *
	 * @param string $post_type
	 * @param string $taxonomy
	 * @return array Posts
	 */
	function get_posts_sorted_by_taxonomy( $post_type, $taxonomy = 'category' )
	{
		$cposts 	= array();
		foreach ( $this->get_any_posts_by_posttype( $post_type ) as $p ) {
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
	 * @since 1.2.1
	 *
	 * @param array &$vars
	 * @param array $sortposts
	 * @param const $sort_flags
	 * @return array Posts Variables
	 */
	public static function get_vars_post__in( $vars, $sortposts, $sort_flags = SORT_REGULAR ) {
		if ( $vars['order'] == 'asc' ) {
			asort( $unsorted, SORT_REGULAR );
		}
		else {
			arsort( $unsorted, SORT_REGULAR );
		}

		$vars['orderby']	= 'post__in';
		$vars['post__in']	 = array_keys( $unsorted );

		return $vars;

//		// sort post ids by value
//		if ( $vars['order'] == 'asc' )
//			asort( $sortposts, $sort_flags );
//		else
//			arsort( $sortposts, $sort_flags );
//
//		// this will make sure WP_Query will use the order of the ids that we have just set in 'post__in'
//		// set priority higher then default to prevent conflicts with 3rd party plugins
//		add_filter( 'posts_orderby', array( $this, 'filter_orderby_post__in' ), 10, 2 );
//
//		// cleanup the vars we dont need
//		$vars['order']		= '';
//		$vars['orderby'] 	= '';
//
//		// add the sorted post ids to the query with the use of post__in
//		$vars['post__in'] = array_keys( $sortposts );

		//return $vars;
	}

	/**
	 * Get orderby type
	 *
	 * @since 1.1.0
	 *
	 * @param string $orderby
	 * @param string $type
	 * @return array Column
	 */
	private function get_orderby_type( $orderby, $type ) {
		$db_columns = CPAC_Utility::get_stored_columns( $type );

		if ( $db_columns ) {
			foreach ( $db_columns as $id => $vars ) {

				// check which custom column was clicked
				if ( isset( $vars['label'] ) && $orderby == CPAC_Utility::sanitize_string( $vars['label'] ) ) {
					$column[$id] = $vars;
					return apply_filters( 'cpac_get_orderby_type', $column, $orderby, $type );
				}
			}
		}
		return apply_filters( 'cpac_get_orderby_type', false, $orderby, $type );
	}

	/**
	 * Maintain order of ids that are set in the post__in var.
	 *
	 * This will force the returned posts to use the order of the ID's that
	 * have been set in post__in. Without this the ID's will be set in numeric order.
	 * See the WP_Query object for more info about the use of post__in.
	 *
	 * @since 1.2.1
	 *
	 * @param string $orderby
	 * @param object $wp
	 * @return string SQL FIELD
	 */
	public function filter_orderby_post__in( $orderby, $wp ) {
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
	 * @since 1.2.1
	 *
	 * @param string $post_type
	 * @return array Posts
	 */
	public function get_any_posts_by_posttype( $post_type ) {
		$any_posts = (array) get_posts(array(
			'numberposts'	=> -1,
			'post_status'	=> 'any',
			'post_type'		=> $post_type
		));

		// trash posts are not included in the posts_status 'any' by default
		$trash_posts = (array) get_posts(array(
			'numberposts'	=> -1,
			'post_status'	=> 'trash',
			'post_type'		=> $post_type
		));

		$all_posts = array_merge($any_posts, $trash_posts);

		return (array) $all_posts;
	}

	/**
	 * Request URI is
	 *
	 * @since 1.3.1
	 *
	 * @param string $screen_id
	 * @return bool
	 */
	private function request_uri_is( $screen_id = '' ) {
		if (strpos( $_SERVER['REQUEST_URI'], "/{$screen_id}.php" ) !== false )
			return true;

		return false;
	}

	/**
	 * Prepare the value for being by sorting
	 *
	 * @since 1.3.0
	 *
	 * @param string $string
	 * @return string String
	 */
	private function prepare_sort_string_value( $string ) {
		// remove tags and only get the first 20 chars and force lowercase.
		$string = strtolower( substr( CPAC_Utility::strip_trim($string),0 ,20 ) );

		return $string;
	}

	/**
	 * Get users data
	 *
	 * @since 1.3
	 *
	 * @return array Users data
	 */
	function get_users_data() {
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