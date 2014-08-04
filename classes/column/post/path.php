<?php
/**
 * Column displaying path (without URL, e.g. "/my-category/sample-post/") to the front-end location of this item.
 *
 * @since 2.2.3
 */
class CPAC_Column_Post_Path extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.2.3
	 */
	public function init() {

		parent::init();

		// Properties
		$this->properties['type']	 	= 'column-path';
		$this->properties['label']	 	= __( 'Path', 'cpac' );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.2.3
	 */
	public function get_value( $post_id ) {

		return $this->get_raw_value( $post_id );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.2.3
	 */
	public function get_raw_value( $post_id ) {
		return str_replace( home_url(), '', get_permalink( $post_id ) );
	}
}