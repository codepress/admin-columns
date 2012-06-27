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
			
			// actions
			case "column-actions" :
				$result = $this->get_column_value_actions($comment);
				break;
			
			// word count
			case "column-word-count" :
				$result = str_word_count( $this->strip_trim( $comment->comment_content ) );
				break;
			
			default :
				$result = '';
			
		endswitch;
		
		// Filter for customizing the result output
		apply_filters('cpac-comments-column-result', $result, $type, $column_name, $comment_id);
		
		echo $result;
	}
	
	/**
	 *	Get column value of comments actions
	 *
	 *	This part is copied from the Comments List Table class
	 *
	 * 	@since     1.4.2
	 */
	private function get_column_value_actions( $comment ) 
	{	
		global $post, $comment_status;
		
		// set uased vars
		$user_can 			= current_user_can( 'edit_comment', $comment->comment_ID );
		$the_comment_status = wp_get_comment_status( $comment->comment_ID );
		
		if ( $user_can ) {
			$del_nonce = esc_html( '_wpnonce=' . wp_create_nonce( "delete-comment_$comment->comment_ID" ) );
			$approve_nonce = esc_html( '_wpnonce=' . wp_create_nonce( "approve-comment_$comment->comment_ID" ) );

			$url = "comment.php?c=$comment->comment_ID";

			$approve_url = esc_url( $url . "&action=approvecomment&$approve_nonce" );
			$unapprove_url = esc_url( $url . "&action=unapprovecomment&$approve_nonce" );
			$spam_url = esc_url( $url . "&action=spamcomment&$del_nonce" );
			$unspam_url = esc_url( $url . "&action=unspamcomment&$del_nonce" );
			$trash_url = esc_url( $url . "&action=trashcomment&$del_nonce" );
			$untrash_url = esc_url( $url . "&action=untrashcomment&$del_nonce" );
			$delete_url = esc_url( $url . "&action=deletecomment&$del_nonce" );
		}
		
		/** begin - copied from class-wp-comments-list-table */
		if ( $user_can ) {
			// preorder it: Approve | Reply | Quick Edit | Edit | Spam | Trash
			$actions = array(
				'approve' => '', 'unapprove' => '',
				'reply' => '',
				'quickedit' => '',
				'edit' => '',
				'spam' => '', 'unspam' => '',
				'trash' => '', 'untrash' => '', 'delete' => ''
			);

			if ( $comment_status && 'all' != $comment_status ) { // not looking at all comments
				if ( 'approved' == $the_comment_status )
					$actions['unapprove'] = "<a href='$unapprove_url' class='delete:the-comment-list:comment-$comment->comment_ID:e7e7d3:action=dim-comment&amp;new=unapproved vim-u vim-destructive' title='" . esc_attr__( 'Unapprove this comment' ) . "'>" . __( 'Unapprove' ) . '</a>';
				else if ( 'unapproved' == $the_comment_status )
					$actions['approve'] = "<a href='$approve_url' class='delete:the-comment-list:comment-$comment->comment_ID:e7e7d3:action=dim-comment&amp;new=approved vim-a vim-destructive' title='" . esc_attr__( 'Approve this comment' ) . "'>" . __( 'Approve' ) . '</a>';
			} else {
				$actions['approve'] = "<a href='$approve_url' class='dim:the-comment-list:comment-$comment->comment_ID:unapproved:e7e7d3:e7e7d3:new=approved vim-a' title='" . esc_attr__( 'Approve this comment' ) . "'>" . __( 'Approve' ) . '</a>';
				$actions['unapprove'] = "<a href='$unapprove_url' class='dim:the-comment-list:comment-$comment->comment_ID:unapproved:e7e7d3:e7e7d3:new=unapproved vim-u' title='" . esc_attr__( 'Unapprove this comment' ) . "'>" . __( 'Unapprove' ) . '</a>';
			}

			if ( 'spam' != $the_comment_status && 'trash' != $the_comment_status ) {
				$actions['spam'] = "<a href='$spam_url' class='delete:the-comment-list:comment-$comment->comment_ID::spam=1 vim-s vim-destructive' title='" . esc_attr__( 'Mark this comment as spam' ) . "'>" . /* translators: mark as spam link */ _x( 'Spam', 'verb' ) . '</a>';
			} elseif ( 'spam' == $the_comment_status ) {
				$actions['unspam'] = "<a href='$unspam_url' class='delete:the-comment-list:comment-$comment->comment_ID:66cc66:unspam=1 vim-z vim-destructive'>" . _x( 'Not Spam', 'comment' ) . '</a>';
			} elseif ( 'trash' == $the_comment_status ) {
				$actions['untrash'] = "<a href='$untrash_url' class='delete:the-comment-list:comment-$comment->comment_ID:66cc66:untrash=1 vim-z vim-destructive'>" . __( 'Restore' ) . '</a>';
			}

			if ( 'spam' == $the_comment_status || 'trash' == $the_comment_status || !EMPTY_TRASH_DAYS ) {
				$actions['delete'] = "<a href='$delete_url' class='delete:the-comment-list:comment-$comment->comment_ID::delete=1 delete vim-d vim-destructive'>" . __( 'Delete Permanently' ) . '</a>';
			} else {
				$actions['trash'] = "<a href='$trash_url' class='delete:the-comment-list:comment-$comment->comment_ID::trash=1 delete vim-d vim-destructive' title='" . esc_attr__( 'Move this comment to the trash' ) . "'>" . _x( 'Trash', 'verb' ) . '</a>';
			}

			if ( 'spam' != $the_comment_status && 'trash' != $the_comment_status ) {
				$actions['edit'] = "<a href='comment.php?action=editcomment&amp;c={$comment->comment_ID}' title='" . esc_attr__( 'Edit comment' ) . "'>". __( 'Edit' ) . '</a>';
				$actions['quickedit'] = '<a onclick="commentReply.open( \''.$comment->comment_ID.'\',\''.$post->ID.'\',\'edit\' );return false;" class="vim-q" title="'.esc_attr__( 'Quick Edit' ).'" href="#">' . __( 'Quick&nbsp;Edit' ) . '</a>';
				$actions['reply'] = '<a onclick="commentReply.open( \''.$comment->comment_ID.'\',\''.$post->ID.'\' );return false;" class="vim-r" title="'.esc_attr__( 'Reply to this comment' ).'" href="#">' . __( 'Reply' ) . '</a>';
			}

			$actions = apply_filters( 'comment_row_actions', array_filter( $actions ), $comment );

			$i = 0;
			$result = '<div class="cp-row-actions">';
			foreach ( $actions as $action => $link ) {
				++$i;
				( ( ( 'approve' == $action || 'unapprove' == $action ) && 2 === $i ) || 1 === $i ) ? $sep = '' : $sep = ' | ';

				// Reply and quickedit need a hide-if-no-js span when not added with ajax
				if ( ( 'reply' == $action || 'quickedit' == $action ) && ! defined('DOING_AJAX') )
					$action .= ' hide-if-no-js';
				elseif ( ( $action == 'untrash' && $the_comment_status == 'trash' ) || ( $action == 'unspam' && $the_comment_status == 'spam' ) ) {
					if ( '1' == get_comment_meta( $comment->comment_ID, '_wp_trash_meta_status', true ) )
						$action .= ' approve';
					else
						$action .= ' unapprove';
				}

				$result .= "<span class='$action'>$sep$link</span>";
			}
			$result .= '</div>';
		}
		return $result;
		// end copied
	}
}

?>