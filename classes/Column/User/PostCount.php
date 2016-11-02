<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_User_PostCount extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-user_postcount' );
		$this->set_label( __( 'Post Count', 'codepress-admin-columns' ) );
	}

	/**
	 * Get count
	 *
	 * @since 2.0
	 */
	public function get_count( $user_id ) {
		return ac_helper()->user->get_postcount( $user_id, $this->get_option( 'post_type' ) );
	}

	public function get_value( $user_id ) {
		$value = $this->get_empty_char();

		$count = $this->get_raw_value( $user_id );
		if ( $count > 0 ) {
			$link = add_query_arg( array( 'post_type' => $this->get_option( 'post_type' ), 'author' => $user_id ), admin_url( 'edit.php' ) );
			$value = ac_helper()->html->link( $link, $count );
		}

		return $value;
	}

	public function get_raw_value( $user_id ) {
		return $this->get_count( $user_id );
	}

	public function display_settings() {

		$post_types = array();
		foreach ( (array) $this->get_post_types() as $type ) {
			$obj = get_post_type_object( $type );
			$post_types[ $type ] = $obj->labels->name;
		}

		$this->field_settings->field( array(
			'type'    => 'select',
			'name'    => 'post_type',
			'label'   => __( 'Post Type', 'codepress-admin-columns' ),
			'options' => $post_types,
			'section' => true,
		) );
	}

	private function get_post_types() {
		$post_types = array();

		if ( post_type_exists( 'post' ) ) {
			$post_types['post'] = 'post';
		}
		if ( post_type_exists( 'page' ) ) {
			$post_types['page'] = 'page';
		}

		$post_types = array_merge( $post_types, get_post_types( array(
			'_builtin' => false,
			'show_ui'  => true,
		) ) );

		/**
		 * Filter the post types for which Admin Columns is active
		 *
		 * @since 2.0
		 *
		 * @param array $post_types List of active post type names
		 */
		return apply_filters( 'cac/post_types', $post_types );
	}

}