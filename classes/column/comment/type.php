<?php
/**
 * @since 2.4.2
 */
class CPAC_Column_Comment_Type extends CPAC_Column {

	public function init() {

		parent::init();

		// Properties
		$this->properties['type']	 	= 'column-type';
		$this->properties['label']	 	= __( 'Type', 'codepress-admin-columns' );
	}

	public function get_value( $id ) {
		return $this->get_raw_value( $id );
	}

	public function get_raw_value( $id ) {
		$comment = get_comment( $id );
		return $comment->comment_type;
	}
}