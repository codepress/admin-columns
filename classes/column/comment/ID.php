<?php
/**
 * CPAC_Column_Comment_ID
 *
 * @since 2.0
 */
class CPAC_Column_Comment_ID extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.2.1
	 */
	public function init() {

		parent::init();

		// Properties
		$this->properties['type']	 	= 'column-comment_id';
		$this->properties['label']	 	= __( 'ID', 'cpac' );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0
	 */
	function get_value( $id ) {

		return $id;
	}
}