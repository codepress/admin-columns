<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_User_PostCount extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-user_postcount';
		$this->properties['label'] = __( 'Post Count', 'codepress-admin-columns' );
	}

	/**
	 * Get count
	 *
	 * @since 2.0
	 */
	public function get_count( $user_id ) {
		return ac_helper()->user->get_postcount( $user_id, $this->get_option( 'post_type' ) );
	}

	function get_value( $user_id ) {
		$value = $this->get_empty_char();

		$count = $this->get_raw_value( $user_id );
		if ( $count > 0 ) {
			$value = "<a href='edit.php?post_type=" . $this->get_option( 'post_type' ) . "&author={$user_id}'>{$count}</a>";
		}

		return $value;
	}

	function get_raw_value( $user_id ) {
		return $this->get_count( $user_id );
	}

	function display_settings() {

		$post_types = array();
		foreach ( (array) cpac()->get_post_types() as $type ) {
			$obj = get_post_type_object( $type );
			$post_types[ $type ] = $obj->labels->name;
		}

		$this->field_settings->field( array(
			'type'    => 'select',
			'name'    => 'post_type',
			'label'   => __( 'Post Type', 'codepress-admin-columns' ),
			'options' => $post_types,
			'section' => true
		) );
	}

}