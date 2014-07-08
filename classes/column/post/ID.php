<?php
/**
 * CPAC_Column_Post_ID
 *
 * @since 2.0
 */
class CPAC_Column_Post_ID extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.2.1
	 */
	public function init() {

		parent::init();

		// Properties
		$this->properties['type']	 	= 'column-postid';
		$this->properties['label']	 	= __( 'ID', 'cpac' );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0
	 */
	function get_value( $post_id ) {
		return $this->get_raw_value( $post_id );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0.3
	 */
	function get_raw_value( $post_id ) {
		return $post_id;
	}
}