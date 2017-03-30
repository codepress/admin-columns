<?php

/**
 * @since 2.0
 */
class AC_Column_Post_Status extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-status' );
		$this->set_label( __( 'Status', 'codepress-admin-columns' ) );
	}

	public function get_value( $post_id ) {
		global $wp_post_statuses;

		$post_status = $this->get_raw_value( $post_id );

		if ( ! isset( $wp_post_statuses[ $post_status ] ) ) {
			return false;
		}

		$label = $wp_post_statuses[ $post_status ]->label;

		if ( 'future' === $post_status ) {
			$label .= " <p class='description'>" . date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( get_post_field( 'post_date', $post_id ) ) ) . "</p>";
		}

		return $label;
	}

	public function get_raw_value( $post_id ) {
		return get_post_field( 'post_status', $post_id );
	}

}