<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Post_Excerpt extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-excerpt' );
		$this->set_label( __( 'Excerpt', 'codepress-admin-columns' ) );
	}

	public function get_value( $post_id ) {
		$excerpt = ac_helper()->post->excerpt( $post_id );

		$value = $this->get_setting( 'word_limit' )->format( $excerpt );
		if ( $value && ! has_excerpt( $post_id ) ) {
			$value = '<span class="cpac-inline-info">' . __( 'Excerpt from content', 'codepress-admin-columns' ) . '</span> ' . $value;
		}

		return $value;
	}

	public function get_raw_value( $post_id ) {
		return ac_helper()->post->get_raw_field( 'post_excerpt', $post_id );
	}

	public function register_settings() {
		$this->add_setting( new AC_Settings_Setting_WordLimit( $this ) );
	}

}