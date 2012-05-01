<?php

/**
 * CPAC_Comments_Values Class
 *
 * @since     1.4.4
 *
 */
class CPAC_Comments_Values extends CPAC_Values
{	
	/**
	 * Constructor
	 *
	 * @since     1.4.4
	 */
	function __construct()
	{
		parent::__construct();
		
		add_action( 'manage_comments_custom_column', array( $this, 'manage_comments_column_value'), 10, 2 );
	}
	
	/**
	 * Manage custom column for Comments
	 *
	 * @since     1.3.1
	 */
	public function manage_comments_column_value( $column_name, $comment_id )
	{
		$type = $column_name;
		
		// comments object
		$comment = get_comment($comment_id);
		
		// Check for custom fields, such as column-meta-[customfieldname]
		if ( $this->is_column_meta($type) )
			$type = 'column-comment-meta';
		
		// Hook 
		do_action('cpac-manage-comments-column', $type, $column_name, $comment_id);
		
		$result = '';
		switch ($type) :			
			
			// comment id
			case "column-comment_id" :
				$result = $comment_id;
				break;
			
			// author
			case "column-author_author" :
				$result = $comment->comment_author;
				break;
				
			// avatar
			case "column-author_avatar" :
				$result = get_avatar( $comment, 80 );				
				break;
				
			// url
			case "column-author_url" :				
				$result	= $this->get_shorten_url($comment->comment_author_url);				
				break;
				
			// ip
			case "column-author_ip" :
				$result = $comment->comment_author_IP;
				break;
				
			// email
			case "column-author_email" :
				$result = $comment->comment_author_email;
				break;
				
			// parent
			case "column-reply_to" :
				if ( $comment->comment_approved ) {				
					$parent 		= get_comment( $comment->comment_parent );
					$parent_link 	= esc_url( get_comment_link( $comment->comment_parent ) );
					$name 			= get_comment_author( $parent->comment_ID );
					$result 		= sprintf( '<a href="%1$s">%2$s</a>', $parent_link, $name );
				}
				break;	
			
			// approved
			case "column-approved" :
				$result = $this->get_asset_image('no.png');
				if ( $comment->comment_approved )
					$result = $this->get_asset_image('checkmark.png');
				break;
			
			// date
			case "column-date" :
				$comment_url = esc_url( get_comment_link( $comment_id ) );
				$result 	 = sprintf( __( 'Submitted on <a href="%1$s">%2$s at %3$s</a>' ), 
					$comment_url,
					$this->get_date($comment->comment_date),
					$this->get_time($comment->comment_date)
				);
				$result 	 = "<div class='submitted-on'>{$result}</div>";
				break;
			
			// date GMT
			case "column-date_gmt" :
				$comment_url = esc_url( get_comment_link( $comment_id ) );
				$result 	 = sprintf( __( 'Submitted on <a href="%1$s">%2$s at %3$s</a>' ), 
					$comment_url,
					$this->get_date($comment->comment_date_gmt),
					$this->get_time($comment->comment_date_gmt)
				);
				$result 	 = "<div class='submitted-on'>{$result}</div>";
				break;
				
			// custom field
			case "column-comment-meta" :
				$result = $this->get_column_value_custom_field($comment_id, $column_name, 'comment');		
				break;
				
			// agent
			case "column-agent" :
				$result = $comment->comment_agent;		
				break;	
				
			// excerpt
			case "column-excerpt" :
				$comment 	= get_comment($comment_id);
				$result 	= $this->get_shortened_string($comment->comment_content, $this->excerpt_length);
				break;	
			
			default :
				$result = '';
			
		endswitch;
		
		echo $result;
	}
}

?>