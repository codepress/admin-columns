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
		$value = parent::get_value( $id );

		$post = get_post( $id );

		switch ( get_post_status( $id ) ) {

			// Icons
			case 'private' :
			case 'draft' :
			case 'pending' :
			case 'future' :
				$value = ac_helper()->post->get_status_icon( $post );

				break;

				// Tooltip
			default :
				$value = ac_helper()->html->tooltip( $value, ac_helper()->date->date( $post->post_date, 'wp_date_time' ) );
		}

		return $value;
	}

	public function get_raw_value( $post_id ) {
		$post = get_post( $post_id );

		return $post->post_date;
	}

	public function register_settings() {
		$this->add_setting( new AC_Settings_Column_Date( $this ) );
	}

}