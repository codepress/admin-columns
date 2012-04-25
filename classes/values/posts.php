<?php

/**
 * CPAC_Posts_Values Class
 *
 * @since     1.4.4
 *
 */
class CPAC_Posts_Values extends CPAC_Values
{	
	/**
	 * Constructor
	 *
	 * @since     1.4.4
	 */
	function __construct()
	{		
		parent::__construct();
		
		add_action( 'manage_pages_custom_column', array( $this, 'manage_posts_column_value'), 10, 2 );	
		add_action( 'manage_posts_custom_column', array( $this, 'manage_posts_column_value'), 10, 2 );
	}
	
	/**
	 * Manage custom column for Post Types.
	 *
	 * @since     1.0
	 */
	public function manage_posts_column_value($column_name, $post_id) 
	{
		$type = $column_name;

		// Check for taxonomies, such as column-taxonomy-[taxname]	
		if ( strpos($type, 'column-taxonomy-') !== false )
			$type = 'column-taxonomy';
		
		// Check for custom fields, such as column-meta-[customfieldname]
		if ( $this->is_column_meta($type) )
			$type = 'column-post-meta';
		
		// Hook 
		do_action('cpac-manage-posts-column', $type, $column_name, $post_id);
	
		// Switch Types
		$result = '';
		switch ($type) :			
			
			// Post ID
			case "column-postid" :
				$result = $post_id;
				break;
			
			// Excerpt
			case "column-excerpt" :
				$result = $this->get_post_excerpt($post_id);
				break;
			
			// Featured Image
			case "column-featured_image" :
				if ( has_post_thumbnail($post_id) )
					$result = get_the_post_thumbnail($post_id, array(80,80));			
				break;
				
			// Sticky Post
			case "column-sticky" :
				if ( is_sticky($post_id) )					
					$result = $this->get_asset_image('checkmark.png');
				break;
			
			// Order
			case "column-order" :
				$result = get_post_field('menu_order', $post_id);			
				break;
				
			// Post Formats
			case "column-post_formats" :
				$result = get_post_format($post_id);			
				break;
			
			// Page template
			case "column-page-template" :
				// file name
				$page_template 	= get_post_meta($post_id, '_wp_page_template', true);			

				// get template nice name
				$result = array_search($page_template, get_page_templates());			
				break;
			
			// Slug
			case "column-page-slug" :
				$result = get_post($post_id)->post_name;
				break;
			
			// Slug
			case "column-word-count" :
				$result = str_word_count( $this->strip_trim( get_post($post_id)->post_content ) );
				break;
			
			// Taxonomy
			case "column-taxonomy" :
				$tax 	= str_replace('column-taxonomy-', '', $column_name);
				$tags 	= get_the_terms($post_id, $tax);
				$tarr 	= array();
				
				// for post formats we will display standard instead of empty
				if ( $tax == 'post_format' && empty($tags) ) {
					$result = __('Standard');
				}
				
				// add name with link
				elseif ( !empty($tags) ) {	
					$post_type = get_post_type($post_id);
					foreach($tags as $tag) {
						// sanatize title
						if ( isset($tag->term_id) ) {
							$tax_title 	= esc_html(sanitize_term_field('name', $tag->name, $tag->term_id, $tag->taxonomy, 'edit'));
							$tarr[] 	= "<a href='edit.php?post_type={$post_type}&{$tag->taxonomy}={$tag->slug}'>{$tax_title}</a>";
						}
					}
					$result = implode(', ', $tarr);
				}			
				break;
			
			// Custom Field
			case "column-post-meta" :
				$result = $this->get_column_value_custom_field($post_id, $column_name, 'post');		
				break;
			
			// Attachment
			case "column-attachment" :
				$result = $this->get_column_value_attachments($post_id);
				break;
				
			// Attachment count
			case "column-attachment-count" :
				$result = count($this->get_attachment_ids($post_id));
				break;
				
			// Roles
			case "column-roles" :
				$user_id 	= get_post($post_id)->post_author;
				$userdata 	= get_userdata( $user_id );
				if ( !empty($userdata->roles[0]) )
					echo implode(', ',$userdata->roles);
				break;
			
			// Post status
			case "column-status" :
				$p 		= get_post($post_id);
				$result = $p->post_status;
				if ( $result == 'future')
					$result = $result . " <p class='description'>" . date_i18n( get_option('date_format') . ' ' . get_option('time_format') , strtotime($p->post_date) ) . "</p>";
				break;
				
			// Post comment status
			case "column-comment-status" :
				$p 		= get_post($post_id);
				$result = $this->get_asset_image('no.png', $p->comment_status);
				if ( $p->comment_status == 'open' )
					$result = $this->get_asset_image('checkmark.png', $p->comment_status);
				break;
				
			// Post ping status
			case "column-ping-status" :
				$p 		= get_post($post_id);
				$result = $this->get_asset_image('no.png', $p->ping_status);
				if ( $p->ping_status == 'open' )
					$result = $this->get_asset_image('checkmark.png', $p->ping_status);
				break;
			
			// Post actions ( delete, edit etc. )
			case "column-actions" :
				$result = $this->get_column_value_actions($post_id, 'posts');
				break;
			
			default :
				$result = '';
						
		endswitch;
		
		echo $result;	
	}
}

new CPAC_Posts_Values();

?>