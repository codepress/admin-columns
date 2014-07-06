<?php
/**
 * CPAC_Column_Post_Ping_Status
 *
 * @since 2.0
 */
class CPAC_Column_Post_Ping_Status extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.2.1
	 */
	public function init() {

		parent::init();

		// Properties
		$this->properties['type']				= 'column-ping_status';
		$this->properties['label']				= __( 'Ping status', 'cpac' );
		$this->properties['object_property']	= 'ping_status';
	}

	/**
	 * @see CPAC_Column::apply_conditional()
	 * @since 2.2
	 */
	function apply_conditional() {

		return post_type_supports( $this->storage_model->key, 'comments' );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0
	 */
	function get_value( $post_id ) {

		$ping_status = $this->get_raw_value( $post_id );

		$value = $this->get_asset_image( 'no.png', $ping_status );
		if ( 'open' == $ping_status )
			$value = $this->get_asset_image( 'checkmark.png', $ping_status );

		return $value;
	}

	/**
	 * @see CPAC_Column::get_raw_value()
	 * @since 2.0.3
	 */
	function get_raw_value( $post_id ) {

		return get_post_field( 'ping_status', $post_id, 'raw' );
	}
}