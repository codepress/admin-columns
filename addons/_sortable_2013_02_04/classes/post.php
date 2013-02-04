<?php

/**
 * Addon class
 *
 * @since 0.1
 *
 */
class CAC_Addon_Sortable_Post {	
	
	/**
	 * Constructor
	 *
	 * @since 0.1
	 */
	function __construct() {

		// register sortable
		add_action( 'admin_init', array( $this, 'register_sortable_columns' ) );
		
		// handle sortable request
		add_filter( 'request', array( $this, 'handle_requests_orderby_column'), 1 );
	}
	
	/**
	 * 	Register sortable columns
	 *
	 *	Hooks into apply_filters( "manage_{$screen->id}_sortable_columns" ) which is found in class-wp-list-table.php
	 *
	 * 	@since 0.1
	 */
	function register_sortable_columns() {

		global $cpac;
		
		foreach ( $cpac->storage_models as $storage_model ) {
			
			if ( 'post' == $storage_model->type ) {
				add_filter( "manage_edit-{$storage_model->key}_sortable_columns", array( $this, 'manage_sortable_columns' ) );
			}
		}
	}
	
	/**
	 * Callback add Posts sortable column
	 *
	 * @since 1.0.0
	 *
	 * @param array $columns
	 * @return array Sortable Columns
	 */
	public function manage_sortable_columns( $columns ) {
		
		global $post_type, $cpac;
 
		// in some cases post_type is an array ( when clicking a tag inside the overview screen icm CCTM )
		// then we use this as a fallback so we get a string
		if ( is_array( $post_type ) )
			$post_type = $_REQUEST['post_type'];
		
		// storage model exists?
		if ( ! $storage_model = $cpac->get_storage_model( $post_type ) )
			return $columns;

		if ( $_columns = $storage_model->get_columns() ) {
			foreach ( $_columns as $column ) {
				
				// column needs sort?
				if ( 'on' == $column->options->state && isset( $column->options->sort  ) && 'on' == $column->options->sort )				
					$columns[ $column->properties->name ] = CPAC_Utility::sanitize_string( $column->options->label );
			}
		}

		return $columns;
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

		$options[ $type ] = array(
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
	 * @todo active state in header
	 * @param array &$vars
	 * @param string $type
	 */
	function apply_sorting_preference( &$vars, $type ) {
		
		// user has not sorted
		if ( empty( $vars['orderby'] ) ) {
						
			$options = get_user_meta( get_current_user_id(), 'cpac_sorting_preference', true );

			// did the user sorted this column some other time?
			if ( ! empty( $options[ $type ] ) ) {
				$vars['orderby'] = $options[ $type ]['orderby'];
				$vars['order'] 	 = $options[ $type ]['order'];

				// used by active state in column header
				//$_GET['orderby'] = $options[ $type ]['orderby'];
				//$_GET['order']	 = $options[ $type ]['order'];
			}
		}

		// save the order preference
		if ( ! empty( $vars['orderby'] ) ) {

			$options = get_user_meta( get_current_user_id(), 'cpac_sorting_preference', true );

			$options[ $type ] = array(
				'orderby'	=> $vars['orderby'],
				'order'		=> $vars['order']
			);

			update_user_meta( get_current_user_id(), 'cpac_sorting_preference', $options );			
		}
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
	private function get_orderby_type( $orderby, $storage_model ) {
		global $cpac;
		
		$column = array();

		if ( $columns = $storage_model->get_stored_columns() ) {			
			foreach ( $columns as $name => $options ) {
				if ( isset( $options['label'] ) && $orderby == esc_url( $options['label'] ) ) {
					$column[ $name ] = $options;
				}
			}
		}
		
		return apply_filters( 'cpac_get_orderby_type', $column, $orderby, $storage_model->key );
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

		if ( empty( $vars['post_type'] ) )
			return $vars;
		
		$post_type = $vars['post_type'];
		
		// apply sorting preference
		$this->apply_sorting_preference( $vars, $post_type );
		
		// no sorting
		if ( empty( $vars['orderby'] ) )
			return $vars;
		
		global $cpac;
		
		// storage model exists?
		if ( ! $storage_model = $cpac->get_storage_model( $post_type ) )
			return $columns;

		// Column
		$column = $this->get_orderby_type( $vars['orderby'], $storage_model );

		if ( empty( $column ) )
			return $vars;

		// unsorted Posts
		$cposts = array();

		switch ( $column->properties->type ) :

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

				$field_type = 'meta_value';
				if ( in_array( $column->options->field_type, array( 'numeric', 'library_id') ) )
					$field_type = 'meta_value_num';

				$vars = array_merge($vars, array(
					'meta_key' 	=> $column->options->field,
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
				$taxonomy 	= CPAC_Utility::get_taxonomy_by_column_name( $column->properties->name );
				$cposts 	= $this->get_posts_sorted_by_taxonomy( $post_type, $taxonomy );
				break;

			case 'column-author-name' :
				$sort_flag  = SORT_STRING;
				$display_as = $column[$column->properties->name]['display_as'];
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
		

		return $vars;
	}
}

/**
 * Init Class CAC_Addon_Sorting
 *
 * @since 0.1
 */
new CAC_Addon_Sortable_Post();