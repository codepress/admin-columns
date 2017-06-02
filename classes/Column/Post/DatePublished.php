<?php

/**
 * @since 2.4
 */
class AC_Column_Post_DatePublished extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-date_published' );
		$this->set_label( __( 'Date Published' ) );
	}

	public function get_value( $id ) {
		$date = $this->get_raw_value( $id );
		if ( 'publish' !== get_post_status( $id ) ) {
			$date .= sprintf( ' <span class="dashicons dashicons-hidden gray" data-ac-tip="%s"></span>', __( 'Not Published', 'codepress-admin-columns' ) );
		}

		return $date;
	}

	public function get_raw_value( $post_id ) {
		$post = get_post( $post_id );

		return $post->post_date;
	}

	public function register_settings() {
		$this->add_setting( new AC_Settings_Column_Date( $this ) );
	}

}