<?php
/**
 * CPAC_Column_Attached_To
 *
 * @since 2.0
 */
class CPAC_Column_Attached_To extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.2.1
	 */
	public function init() {

		parent::init();

		// Properties
		$this->properties['type']	 	= 'column-attached_to';
		$this->properties['label']	 	= __( 'Attached to post', 'cpac' );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0
	 */
	public function get_value( $id ) {
		$value = false;
		if ( $attached_to = $this->get_raw_value() ) {
			$value = get_the_title( $attached_to );
			if ( $edit_link = get_edit_post_link( $id ) ) {
				$value = '<a href="' . $edit_link . '">' . $value . '</a>';
			}
		}
		return $value;
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0
	 */
	public function get_raw_value( $id ) {
		return get_post_field( $id, 'post_parent' );
	}
}