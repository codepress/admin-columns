<?php
/**
 * CPAC_Column_Post_Page_Template
 *
 * @since 2.0.0
 */
class CPAC_Column_Post_Page_Template extends CPAC_Column {

	function __construct( $storage_model ) {

		$this->properties['type']	 	= 'column-page_template';
		$this->properties['label']	 	= __( 'Page Template', 'cpac' );

		parent::__construct( $storage_model );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0.0
	 */
	function get_value( $post_id ) {

		return array_search( $this->get_raw_value( $post_id ), get_page_templates() );
	}

	/**
	 * @see CPAC_Column::get_raw_value()
	 * @since 2.0.3
	 */
	function get_raw_value( $post_id ) {

		return get_post_meta( $post_id, '_wp_page_template', true );
	}

	/**
	 * @see CPAC_Column::apply_conditional()
	 * @since 2.0.0
	 */
	function apply_conditional() {

		if ( 'page' == $this->storage_model->key )
			return true;

		return false;
	}
}