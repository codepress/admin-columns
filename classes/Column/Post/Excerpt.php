<?php

/**
 * @since 2.0
 */
class AC_Column_Post_Excerpt extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-excerpt' );
		$this->set_label( __( 'Excerpt', 'codepress-admin-columns' ) );
	}

	public function get_value( $post_id ) {
		$value = parent::get_value( $post_id );

		if ( $value && ! has_excerpt( $post_id ) && $value !== $this->get_empty_char() ) {
			$value = ac_helper()->html->tooltip( ac_helper()->icon->dashicon( array( 'icon' => 'flag', 'class' => 'gray' ) ), __( 'Excerpt is missing.' ) . ' ' . __( 'Current excerpt is generated from the content.' ) ) . ' ' . $value;
		}

		return $value;
	}

	public function get_raw_value( $post_id ) {
		return ac_helper()->post->excerpt( $post_id );
	}

	public function register_settings() {
		$this->add_setting( new AC_Settings_Column_WordLimit( $this ) );
	}

}