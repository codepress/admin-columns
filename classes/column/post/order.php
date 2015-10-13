<?php
/**
 * CPAC_Column_Post_Order
 *
 * @since 2.0
 */
class CPAC_Column_Post_Order extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.2.1
	 */
	public function init() {

		parent::init();

		// Properties
		$this->properties['type'] = 'column-order';
		$this->properties['label'] = __( 'Order', 'codepress-admin-columns' );
	}

	/**
	 * @see CPAC_Column::apply_conditional()
	 * @since 2.0
	 */
	public function apply_conditional() {
		return is_post_type_hierarchical( $this->storage_model->post_type ) || post_type_supports( $this->storage_model->post_type, 'page-attributes' );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0
	 */
	public function get_value( $post_id ) {
		return $this->get_raw_value( $post_id );
	}

	/**
	 * @see CPAC_Column::get_raw_value()
	 * @since 2.0.3
	 */
	public function get_raw_value( $post_id ) {
		return get_post_field( 'menu_order', $post_id );
	}
}