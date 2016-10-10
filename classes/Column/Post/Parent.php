<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Post_Parent extends AC_ColumnPostAbstract  {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-parent';
		$this->properties['label'] = __( 'Parent', 'codepress-admin-columns' );
	}

	public function get_value( $post_id ) {
		$parent_id = $this->get_raw_value( $post_id );

		return $parent_id ? ac_helper()->html->link( get_edit_post_link( $parent_id ), ac_helper()->post->get_raw_field( 'post_title', $parent_id ) ) : false;
	}

	public function get_raw_value( $post_id ) {
		$parent_id = ac_helper()->post->get_raw_field( 'post_parent', $post_id );

		return $parent_id && is_numeric( $parent_id ) ? $parent_id : false;
	}

	public function apply_conditional() {
		return is_post_type_hierarchical( $this->get_post_type() );
	}

}