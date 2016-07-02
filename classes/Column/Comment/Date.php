<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Comment_Date extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-date';
		$this->properties['label'] = __( 'Date', 'codepress-admin-columns' );
	}

	public function get_value( $id ) {
		$date = $this->get_raw_value( $id );

		$value = sprintf( __( 'Submitted on <a href="%1$s">%2$s at %3$s</a>' ),
			esc_url( get_comment_link( $id ) ),
			$this->get_date( $date ),
			$this->get_time( $date )
		);

		return "<div class='submitted-on'>{$value}</div>";
	}

	public function get_raw_value( $id ) {
		$comment = get_comment( $id );

		return $comment->comment_date;
	}
}