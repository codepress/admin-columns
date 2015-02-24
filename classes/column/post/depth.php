<?php
/**
 * Depth of the current page (number of ancestors + 1)
 *
 * @since 2.3.4
 */
class CPAC_Column_Post_Depth extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.3.4
	 */
	public function init() {

		parent::init();

		// Properties
		$this->properties['type'] = 'column-depth';
		$this->properties['label'] = __( 'Depth', 'cpac' );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.3.4
	 */
	public function get_value( $post_id ) {

		return $this->get_raw_value( $post_id );
	}

	/**
	 * @see CPAC_Column::get_raw_value()
	 * @since 2.3.4
	 */
	public function get_raw_value( $post_id ) {

		return count( get_post_ancestors( $post_id ) ) + 1;
	}

	/**
	 * @see CPAC_Column::apply_conditional()
	 * @since 2.0
	 */
	public function apply_conditional() {

		return is_post_type_hierarchical( $this->storage_model->get_post_type() );
	}
}