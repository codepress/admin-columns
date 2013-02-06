<?php

/**
 * Addon class
 *
 * @since 0.1
 */
class CAC_Sortable_Model_Post extends CAC_Sortable_Model {	
	
	/**
	 * Constructor
	 *
	 * @since 0.1
	 */
	function __construct( $storage_model ) {
		parent::__construct( $storage_model );
		
		// handle sorting request
		add_filter( 'request', array( $this, 'handle_sorting_request'), 1 );
				
		// enable sorting per column
		add_action( "cpac_get_columns_{$this->storage_model->key}", array( $this, 'enable_sorting' ) );
		
		// register sortable headings
		add_filter( "manage_edit-{$this->storage_model->key}_sortable_columns", array( $this, 'add_sortable_headings' ) );
	}
	
	/**
	 * Enable sorting
	 *
	 * @since 2.0.0
	 */
	function enable_sorting( $columns ) {
		
		$include_types = array(			
			'column-postid',				
			'column-order',				
			'column-modified',				
			'column-comment-count',				
			'column-meta',				
			'column-excerpt',				
			'column-word-count',				
			'column-page-template',				
			'column-post-formats',				
			'column-attachment',				
			'column-attachment-count',				
			'column-page-slug',				
			'column-sticky',				
			'column-featured-image',				
			'column-roles',				
			'column-status',				
			'column-comment-status',				
			'column-ping-status',				
			'column-taxonomy',				
			'column-author-name',				
			'column-before-moretag',

			// WP default columns
			'title',				
			'categories',				
			'tags',				
		);
		
		foreach ( $columns as $column ) {			
			if( in_array( $column->properties->type, $include_types ) ) {
			
				$column->set_properties( 'is_sortable', true );
			}
		}
	}
		
	/**
	 * Add sortable headings
	 *
	 * @since 1.0.0
	 *
	 * @param array $columns
	 * @return array Column name | Sanitized Label
	 */
	public function add_sortable_headings( $columns ) {
		
		global $post_type;
		
		// in some cases post_type is an array ( when clicking a tag inside the overview screen icm CCTM )
		// then we use this as a fallback so we get a string
		if ( is_array( $post_type ) )
			$post_type = $_REQUEST['post_type'];
		
		
		// get columns from storage model.
		// columns that are active and have enbaled sort will be added to the sortable headings.
		if ( $_columns = $this->storage_model->get_columns() ) {		
		
			foreach ( $_columns as $column ) {
				
				if ( $column->properties->is_sortable && 'on' == $column->options->state ) {
										
					if ( 'on' == $column->options->sort ) {				
						$columns[ $column->properties->name ] = CPAC_Utility::sanitize_string( $column->options->label );
					}
					
					if ( 'off' == $column->options->sort ) {						
						unset( $columns[ $column->properties->name ] );
					}					
				}		
			}
		}	
		
		return $columns;
	}
			
	/**
	 * Get posts by post_type
	 *
	 * @since 1.2.1
	 *
	 * @param string $post_type
	 * @return array Posts
	 */
	public function get_posts_by_posttype( $post_type, $fields = '' ) {
		$any_posts = (array) get_posts( array(
			'numberposts'	=> -1,
			'post_status'	=> 'any',
			'post_type'		=> $post_type,
			'fields'		=> $fields
		));

		// trash posts are not included in the posts_status 'any' by default
		$trash_posts = (array) get_posts( array(
			'numberposts'	=> -1,
			'post_status'	=> 'trash',
			'post_type'		=> $post_type,
			'fields'		=> $fields
		));

		$posts = array_unique( array_merge( $any_posts, $trash_posts ) );

		return $posts;
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
	public function handle_sorting_request( $vars ) {

	if ( empty( $vars['post_type'] ) )
			return $vars;
		
		$post_type = $vars['post_type'];
		
		// apply sorting preference
		$this->apply_sorting_preference( $vars, $post_type );
		
		// no sorting
		if ( empty( $vars['orderby'] ) )
			return $vars;

		// Column
		$column = $this->get_orderby_type( $vars['orderby'] );

		if ( empty( $column ) )
			return $vars;
			
		// unsorted Posts
		$posts = array();

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
				foreach ( $this->get_posts_by_posttype( $post_type ) as $p ) {
					$posts[ $p->ID ] = $this->prepare_sort_string_value( $p->post_content );
				}
				break;

			case 'column-word-count' :
				$sort_flag = SORT_NUMERIC;
				foreach ( $this->get_posts_by_posttype( $post_type ) as $p ) {	
					$posts[ $p->ID ] = str_word_count( CPAC_Utility::strip_trim( $p->post_content ) );
				}
				break;

			case 'column-page-template' :
				$sort_flag = SORT_STRING;
				$templates = get_page_templates();
				foreach ( $this->get_posts_by_posttype( $post_type, 'ids' ) as $id ) {
					$page_template  = get_post_meta( $id, '_wp_page_template', true );
					$posts[ $id ] = array_search( $page_template, $templates );
				}
				break;

			case 'column-post_formats' :
				$sort_flag = SORT_REGULAR;
				foreach ( $this->get_posts_by_posttype( $post_type, 'ids' ) as $id ) {
					$posts[ $id ] = get_post_format( $id );
				}
				break;

			case 'column-attachment' :
			case 'column-attachment-count' :
				$sort_flag = SORT_NUMERIC;
				foreach ( $this->get_posts_by_posttype( $post_type, 'ids' ) as $id ) {
					
					$attachments = get_posts( array(
						'post_type' 	=> 'attachment',
						'numberposts' 	=> -1,
						'post_status' 	=> null,
						'post_parent' 	=> $id,
						'fields' 		=> 'ids'
					));
					
					$posts[ $id ] = count( $attachments );
				}
				break;

			case 'column-page-slug' :
				$sort_flag = SORT_REGULAR;
				foreach ( $this->get_posts_by_posttype( $post_type ) as $p ) {
					$posts[ $p->ID ] = $p->post_name;
				}
				break;

			case 'column-sticky' :
				$sort_flag = SORT_REGULAR;
				$stickies = get_option( 'sticky_posts' );
				foreach ( $this->get_posts_by_posttype( $post_type, 'ids' ) as $id ) {
					$posts[ $id ] = $id;
					if ( ! empty( $stickies ) && in_array( $id, $stickies ) ) {
						$posts[ $id ] = 0;
					}
				}
				break;

			case 'column-featured_image' :
				$sort_flag = SORT_REGULAR;
				foreach ( $this->get_posts_by_posttype( $post_type, 'ids' ) as $id ) {
					$posts[ $id ] = $id;
					$thumb = get_the_post_thumbnail( $id );
					if ( ! empty( $thumb ) ) {
						$posts[ $id ] = 0;
					}
				}
				break;

			case 'column-roles' :
				$sort_flag = SORT_STRING;
				foreach ( $this->get_posts_by_posttype( $post_type, 'ids' ) as $id ) {
					$posts[ $id ] = 0;
					$userdata = get_userdata( $id );
					if ( ! empty( $userdata->roles[0] ) ) {
						$posts[ $id ] = $userdata->roles[0];
					}
				}
				break;

			case 'column-status' :
				$sort_flag = SORT_STRING;
				foreach ( $this->get_posts_by_posttype( $post_type ) as $p ) {					
					$posts[ $p->ID ] = $p->post_status . strtotime( $p->ID );
				}
				break;

			case 'column-comment-status' :
				$sort_flag = SORT_STRING;
				foreach ( $this->get_posts_by_posttype( $post_type ) as $p ) {
					$posts[ $p->ID ] = $p->comment_status . strtotime( $p->ID );
				}
				break;

			case 'column-ping-status' :
				$sort_flag = SORT_STRING;
				foreach ( $this->get_posts_by_posttype( $post_type ) as $p ) {
					$posts[ $p->ID ] = $p->ping_status . strtotime( $p->ID );
				}
				break;

			case 'column-taxonomy' :
				$sort_flag 	= SORT_STRING; // needed to sort
				$taxonomy 	= CPAC_Utility::get_taxonomy_by_column_name( $column->properties->name );
				$posts 		= $this->get_posts_sorted_by_taxonomy( $post_type, $taxonomy );
				break;

			case 'column-author-name' :
				$sort_flag  = SORT_STRING;
				$display_as = $column[ $column->properties->name ]['display_as'];
				if ( 'userid' == $display_as ) {
					$sort_flag  = SORT_NUMERIC;
				}
				foreach ( $this->get_posts_by_posttype( $post_type ) as $p ) {
						if ( ! empty( $p->post_author ) ) {
						$name = CPAC_Utility::get_author_field_by_nametype( $display_as, $p->post_author );
						$posts[ $p->ID ] = $name;
					}
				}
				break;

			case 'column-before-moretag' :
				$sort_flag = SORT_STRING;
				foreach ( $this->get_posts_by_posttype( $post_type ) as $p ) {					
					$extended 	= get_extended( $p->post_content );
					$content  	= ! empty( $extended['extended'] ) ? $extended['main'] : '';
					$posts[ $p->ID ] = $this->prepare_sort_string_value( $content );
				}
				break;

			/** native WP columns */

			// categories
			case 'categories' :
				$sort_flag 	= SORT_STRING; // needed to sort
				$posts 	= $this->get_posts_sorted_by_taxonomy( $post_type, 'category' );
				break;

			// tags
			case 'tags' :
				$sort_flag 	= SORT_STRING; // needed to sort
				$posts 	= $this->get_posts_sorted_by_taxonomy( $post_type, 'post_tag' );
				break;

			// custom taxonomies
			// see: case 'column-taxonomy'

		endswitch;


		// we will add the sorted post ids to vars['post__in'] and remove unused vars
		if ( isset( $sort_flag ) ) {
			$vars = $this->get_vars_post__in( $vars, $posts, $sort_flag );
		}		

		return $vars;
	}
}