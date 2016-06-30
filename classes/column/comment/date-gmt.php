<?php
defined( 'ABSPATH' ) or die();

/**
 * CPAC_Column_Comment_Date_Gmt
 *
 * @since 2.0
 */
class CPAC_Column_Comment_Date_Gmt extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-date_gmt';
		$this->properties['label'] = __( 'Date GMT', 'codepress-admin-columns' );
	}

	public function get_value( $id ) {
		$date_gmt = $this->get_raw_value( $id );

		$value = sprintf( __( 'Submitted on <a href="%1$s">%2$s at %3$s</a>' ),
			esc_url( get_comment_link( $id ) ),
			$this->get_date( $date_gmt ),
			$this->get_time( $date_gmt )
		);

		return "<div class='submitted-on'>{$value}</div>";
	}

	public function get_raw_value( $id ) {
		$comment = get_comment( $id );

		return $comment->comment_date_gmt;
	}
}