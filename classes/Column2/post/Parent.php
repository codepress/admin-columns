<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Post_Parent extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-parent';
		$this->properties['label'] = __( 'Parent', 'codepress-admin-columns' );
		$this->properties['object_property'] = 'post_parent';
	}

	public function get_value( $post_id ) {
		if ( ! ( $parent_id = $this->get_raw_value( $post_id ) ) ) {
			return false;
		}

		$title = $this->get_post_title( $parent_id );
		$link = get_edit_post_link( $parent_id );

		return $link ? "<a href='{$link}'>{$title}</a>" : $title;
	}

	public function get_raw_value( $post_id ) {
		$parent_id = ac_helper()->post->get_raw_field( 'post_parent', $post_id );

		return $parent_id && is_numeric( $parent_id ) ? $parent_id : false;
	}

	public function apply_conditional() {
		return is_post_type_hierarchical( $this->get_post_type() );
	}

}