<?php
/**
 * CPAC_Column_Comment_Agent
 *
 * @since 2.0
 */
class CPAC_Column_Comment_Agent extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.2.1
	 */
	public function init() {

		parent::init();

		// Properties
		$this->properties['type']	 = 'column-agent';
		$this->properties['label']	 = __( 'Agent', 'codepress-admin-columns' );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0
	 */
	public function get_value( $id ) {
		return $this->get_raw_value( $id );
	}

	/**
	 * @since 2.4.2
	 */
	public function get_raw_value( $id ) {
		$comment = get_comment( $id );
		return $comment->comment_agent;
	}
}