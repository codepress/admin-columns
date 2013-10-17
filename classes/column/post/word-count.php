<?php
/**
 * CPAC_Column_Post_Word_Count
 *
 * @since 2.0.0
 */
class CPAC_Column_Post_Word_Count extends CPAC_Column {

	function __construct( $storage_model ) {

		$this->properties['type']	 	= 'column-word_count';
		$this->properties['label']	 	= __( 'Word count', 'cpac' );

		parent::__construct( $storage_model );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0.0
	 */
	function get_value( $post_id ) {

		return $this->get_raw_value( $post_id );
	}

	/**
	 * @see CPAC_Column::get_raw_value()
	 * @since 2.0.3
	 */
	function get_raw_value( $post_id ) {

		return str_word_count( $this->strip_trim( get_post_field( 'post_content', $post_id ) ) );
	}

	/**
	 * @see CPAC_Column::apply_conditional()
	 * @since 2.0.0
	 */
	function apply_conditional() {

		if ( post_type_supports( $this->storage_model->key, 'editor' ) ) {
			return true;
		}

		return false;
	}
}