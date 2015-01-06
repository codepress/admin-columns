<?php
/**
 * Depth of the current page (number of ancestors + 1)
 *
 * @since NEWVERSION
 */
class CPAC_Column_Post_Depth extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since NEWVERSION
	 */
	public function init() {

		parent::init();

		// Properties
		$this->properties['type']				= 'column-depth';
		$this->properties['label']				= __( 'Depth', 'cpac' );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since NEWVERSION
	 */
	function get_value( $post_id ) {

		return $this->get_raw_value( $post_id );
	}

	/**
	 * @see CPAC_Column::get_raw_value()
	 * @since NEWVERSION
	 */
	function get_raw_value( $post_id ) {

		return count( get_post_ancestors( $post_id ) ) + 1;
	}
}