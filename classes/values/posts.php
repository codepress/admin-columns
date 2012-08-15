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
				if ( function_exists('has_post_thumbnail') && has_post_thumbnail($post_id) )
					$result = get_the_post_thumbnail($post_id, $this->thumbnail_size);
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
					$result = implode(', ',$userdata->roles);
				break;
			
			// Post status
			case "column-status" :
				$p 		= get_post($post_id);
				$result = $this->get_post_status_friendly_name( $p->post_status );
				if ( $p->post_status == 'future')
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
				$result = $this->get_column_value_actions($post_id);
				break;
			
			// Post Last modified
			case "column-modified" :
				$p 		= get_post($post_id);
				$result = $this->get_date($p->post_modified) . ' ' . $this->get_time($p->post_modified);
				break;
				
			// Post Comment count
			case "column-comment-count" :
				$result = WP_List_Table::comments_bubble( $post_id, get_pending_comments_num( $post_id ) );					
				$result .= $this->get_comment_count_details( $post_id );				
				break;			
			
			// Author Name
			case "column-author-name" :
				$result = $this->get_column_value_authorname($post_id, $column_name);					
				break;
			
			default :
				$result = '';
						
		endswitch;
		
		// Filter for customizing the result output
		apply_filters('cpac-posts-column-result', $result, $type, $column_name, $post_id);
		
		echo $result;	
	}
	
	/**
	 * Returns the friendly name for a given status
	 *
	 * @since     1.4.4
	 */
	private function get_post_status_friendly_name( $status ) 
	{
		$builtin = array(
			'publish' 	=> __( 'Published', CPAC_TEXTDOMAIN ),
			'draft' 	=> __( 'Draft', CPAC_TEXTDOMAIN ),
			'future' 	=> __( 'Scheduled', CPAC_TEXTDOMAIN ),
			'private' 	=> __( 'Private', CPAC_TEXTDOMAIN ),
			'pending' 	=> __( 'Pending Review', CPAC_TEXTDOMAIN ),
			'trash' 	=> __( 'Trash', CPAC_TEXTDOMAIN )
		);
		
		if ( isset($builtin[$status]) )
			$status = $builtin[$status];
		
		return $status;			
	}
	
	/**
	 * Comment count extended
	 *
	 * @since     1.4.4
	 */
	private function get_comment_count_details( $post_id )
	{
		$c = wp_count_comments($post_id);

		$details = '';
		if ( $c->approved ) {
			$url 		= esc_url( add_query_arg( array('p' => $post_id, 'comment_status' => 'approved'), admin_url( 'edit-comments.php' ) ) );
			$details .= "<a href='{$url}' class='cp-approved' title='".__('approved', CPAC_TEXTDOMAIN) . "'>{$c->approved}</a>";
		}
		if ( $c->moderated ) {
			$url 		= esc_url( add_query_arg( array('p' => $post_id, 'comment_status' => 'moderated'), admin_url( 'edit-comments.php' ) ) );
			$details .= "<a href='{$url}' class='cp-moderated' title='".__('pending', CPAC_TEXTDOMAIN) . "'>{$c->moderated}</a>";
		}
		if ( $c->spam ) {
			$url 		= esc_url( add_query_arg( array('p' => $post_id, 'comment_status' => 'spam'), admin_url( 'edit-comments.php' ) ) );
			$details .= "<a href='{$url}' class='cp-spam' title='".__('spam', CPAC_TEXTDOMAIN) . "'>{$c->spam}</a>";
		}
		if ( $c->trash ) {
			$url 		= esc_url( add_query_arg( array('p' => $post_id, 'comment_status' => 'trash'), admin_url( 'edit-comments.php' ) ) );
			$details .= "<a href='{$url}' class='cp-trash' title='".__('trash', CPAC_TEXTDOMAIN) . "'>{$c->trash}</a>";
		}
		
		if ( $details ) 
			return "<p class='description row-actions'>{$details}</p>";
		
		return false;
	}
	
	/**
	 *	Get column value of post actions
	 *
	 *	This part is copied from the Posts List Table class
	 *
	 * 	@since     1.4.2
	 */
	protected function get_column_value_actions( $post_id ) 
	{	
		$actions = array();
		
		$post 				= get_post($post_id);
		$title 				= _draft_or_post_title();
		$post_type_object 	= get_post_type_object( $post->post_type );
		$can_edit_post 		= current_user_can( $post_type_object->cap->edit_post, $post->ID );
		
		if ( $can_edit_post && 'trash' != $post->post_status ) {
			$actions['edit'] = '<a href="' . get_edit_post_link( $post->ID, true ) . '" title="' . esc_attr( __( 'Edit this item' ) ) . '">' . __( 'Edit' ) . '</a>';
			$actions['inline hide-if-no-js'] = '<a href="#" class="editinline" title="' . esc_attr( __( 'Edit this item inline' ) ) . '">' . __( 'Quick&nbsp;Edit' ) . '</a>';
		}
		if ( current_user_can( $post_type_object->cap->delete_post, $post->ID ) ) {
			if ( 'trash' == $post->post_status )
				$actions['untrash'] = "<a title='" . esc_attr( __( 'Restore this item from the Trash' ) ) . "' href='" . wp_nonce_url( admin_url( sprintf( $post_type_object->_edit_link . '&amp;action=untrash', $post->ID ) ), 'untrash-' . $post->post_type . '_' . $post->ID ) . "'>" . __( 'Restore' ) . "</a>";
			elseif ( EMPTY_TRASH_DAYS )
				$actions['trash'] = "<a class='submitdelete' title='" . esc_attr( __( 'Move this item to the Trash' ) ) . "' href='" . get_delete_post_link( $post->ID ) . "'>" . __( 'Trash' ) . "</a>";
			if ( 'trash' == $post->post_status || !EMPTY_TRASH_DAYS )
				$actions['delete'] = "<a class='submitdelete' title='" . esc_attr( __( 'Delete this item permanently' ) ) . "' href='" . get_delete_post_link( $post->ID, '', true ) . "'>" . __( 'Delete Permanently' ) . "</a>";
		}
		if ( $post_type_object->public ) {
			if ( in_array( $post->post_status, array( 'pending', 'draft', 'future' ) ) ) {
				if ( $can_edit_post )
					$actions['view'] = '<a href="' . esc_url( add_query_arg( 'preview', 'true', get_permalink( $post->ID ) ) ) . '" title="' . esc_attr( sprintf( __( 'Preview &#8220;%s&#8221;' ), $title ) ) . '" rel="permalink">' . __( 'Preview' ) . '</a>';
			} elseif ( 'trash' != $post->post_status ) {
				$actions['view'] = '<a href="' . get_permalink( $post->ID ) . '" title="' . esc_attr( sprintf( __( 'View &#8220;%s&#8221;' ), $title ) ) . '" rel="permalink">' . __( 'View' ) . '</a>';
			}
		}	
		
		return implode(' | ', $actions);
	}
	
	/**
	 *	Get column value of Custom Field
	 *
	 * 	@since     1.4.6.1
	 */	
	protected function get_column_value_authorname($post_id, $column_name) 
	{		
		$type = get_post_type($post_id);
		
		// get column
		$columns 	= $this->get_stored_columns($type);
		
		// get the type of author name
		$display_as	= isset($columns[$column_name]['display_as']) ? $columns[$column_name]['display_as'] : '';
		
		// get the author
		$post = get_post($post_id);
		if ( !isset( $post->post_author) )
			return false;
		
		$name = Codepress_Admin_Columns::get_author_field_by_nametype($display_as, $post->post_author);
		
		// filter for customization
		$name = apply_filters('cpac_get_column_value_authorname', $name, $column_name, $post_id );
		
		// add link filter
		$class  = isset( $_GET['author'] ) && $_GET['author'] == $userdata->ID ? ' class="current"' : '';
		
		$name = "<a href='edit.php?post_type={$type}&author={$post->post_author}'{$class}>{$name}</a>";
		
		return $name;
	}
}

?>