<?php
/**
 * CPAC_Column_Comment_Agent
 *
 * @since 2.0.0
 */
class CPAC_Column_Comment_Agent extends CPAC_Column {

	function __construct( $storage_model ) {

		$this->properties['type']	 = 'column-agent';
		$this->properties['label']	 = __( 'Agent', 'cpac' );

		parent::__construct( $storage_model );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0.0
	 */
	function get_value( $id ) {

		$comment = get_comment( $id );

		return $comment->comment_agent;
	}
}