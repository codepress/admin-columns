<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Comment_DateGmt extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-date_gmt' );
		$this->set_label( __( 'Date GMT', 'codepress-admin-columns' ) );
	}

	public function get_value( $id ) {
		$date_gmt = $this->get_raw_value( $id );

		$value = sprintf( __( 'Submitted on <a href="%1$s">%2$s at %3$s</a>' ),
			esc_url( get_comment_link( $id ) ),
			ac_helper()->date->date( $date_gmt ),
			ac_helper()->date->time( $date_gmt )
		);

		return "<div class='submitted-on'>{$value}</div>";
	}

	public function get_raw_value( $id ) {
		$comment = get_comment( $id );

		return $comment->comment_date_gmt;
	}
}