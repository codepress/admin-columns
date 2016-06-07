<?php

/**
 * CPAC_Column_User_Post_Count
 *
 * @since 2.0
 */
class CPAC_Column_User_Post_Count extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.2.1
	 */
	public function init() {
		parent::init();

		$this->properties['type'] = 'column-user_postcount';
		$this->properties['label'] = __( 'Post Count', 'codepress-admin-columns' );
		$this->properties['is_cloneable'] = true;
	}

	/**
	 * Get count
	 *
	 * @since 2.0
	 */
	public function get_count( $user_id ) {
		return $this->get_user_postcount( $user_id, $this->get_option( 'post_type' ) );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0
	 */
	function get_value( $user_id ) {
		$value = $this->get_empty_char();

		$count = $this->get_raw_value( $user_id );
		if ( $count > 0 ) {
			$value = "<a href='edit.php?post_type=" . $this->get_option( 'post_type' ) . "&author={$user_id}'>{$count}</a>";
		}

		return $value;
	}

	/**
	 * @see CPAC_Column::get_raw_value()
	 * @since 2.0.3
	 */
	function get_raw_value( $user_id ) {
		return $this->get_count( $user_id );
	}

	/**
	 * Display Settings
	 *
	 * @see CPAC_Column::display_settings()
	 * @since 2.0
	 */
	function display_settings() {

		$post_types = array();
		foreach ( (array) cpac()->get_post_types() as $type ) {
			$obj = get_post_type_object( $type );
			$post_types[ $type ] = $obj->labels->name;
		}

		$this->form_field( array(
			'type'    => 'select',
			'name'    => 'post_type',
			'label'   => __( 'Post Type', 'codepress-admin-columns' ),
			'options' => $post_types,
			'section' => true
		) );
	}
}