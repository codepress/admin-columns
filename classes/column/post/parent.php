<?php
/**
 * CPAC_Column_Post_Parent
 *
 * @since 2.0
 */
class CPAC_Column_Post_Parent extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.2.1
	 */
	public function init() {

		parent::init();

		// Properties
		$this->properties['type']				= 'column-parent';
		$this->properties['label']				= __( 'Parent', 'cpac' );
		$this->properties['object_property']	= 'post_parent';
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0
	 */
	public function get_value( $post_id ) {

		if ( !( $parent_id = $this->get_raw_value( $post_id ) ) ) {
			return false;
		}

		$title = get_the_title( $parent_id );
		$link  = get_edit_post_link( $parent_id );

		return $link ? "<a href='{$link}'>{$title}</a>" : $title;
	}

	/**
	 * @see CPAC_Column::get_raw_value()
	 * @since 2.0.3
	 */
	public function get_raw_value( $post_id ) {

		$parent_id = get_post_field( 'post_parent', $post_id );

		if ( ! $parent_id || ! is_numeric( $parent_id ) ) {
			return false;
		}

		return $parent_id;
	}

	/**
	 * @see CPAC_Column::apply_conditional()
	 * @since 2.0
	 */
	public function apply_conditional() {

		return is_post_type_hierarchical( $this->storage_model->get_post_type() );
	}
}