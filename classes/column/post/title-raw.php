<?php
/**
 * CPAC_Column_Post_Title_Raw
 *
 * @since 2.2.4
 */
class CPAC_Column_Post_Title_Raw extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.2.4
	 */
	public function init() {

		parent::init();

		// Properties
		$this->properties['type']	 = 'column-title_raw';
		$this->properties['label']	 = __( 'Title without actions', 'cpac' );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.2.4
	 */
	function get_value( $post_id ) {

		return $this->get_raw_value( $post_id );
	}

	/**
	 * @see CPAC_Column::get_raw_value()
	 * @since 2.2.4
	 */
	function get_raw_value( $post_id ) {

		return get_post_field( 'post_title', $post_id );
	}
}