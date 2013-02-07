<?php
/**
 * CPAC_Column_Comment_Excerpt
 *
 * @since 2.0.0
 */
class CPAC_Column_Comment_Excerpt extends CPAC_Column {

	function __construct( $storage_model ) {		
		
		$this->properties['type']	 = 'column-excerpt';
		$this->properties['label']	 = __( 'Excerpt', CPAC_TEXTDOMAIN );
		
		parent::__construct( $storage_model );
	}
	
	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0.0
	 */
	function get_value( $id ) {	
		
		$comment = get_comment( $id );	
		
		return $this->get_shortened_string( $comment->comment_content, 20 );
	}
}