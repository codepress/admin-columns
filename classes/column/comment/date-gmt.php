<?php
/**
 * CPAC_Column_Comment_Date_Gmt
 *
 * @since 2.0
 */
class CPAC_Column_Comment_Date_Gmt extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.2.1
	 */
	public function init() {

		parent::init();

		// Properties
		$this->properties['type']	 = 'column-date_gmt';
		$this->properties['label']	 = __( 'Date GMT', 'codepress-admin-columns' );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0
	 */
	public function get_value( $id ) {

		$date_gmt = $this->get_raw_value( $id );

		$value = sprintf( __( 'Submitted on <a href="%1$s">%2$s at %3$s</a>' ),
			esc_url( get_comment_link( $id ) ),
			$this->get_date( $date_gmt ),
			$this->get_time( $date_gmt )
		);

		return "<div class='submitted-on'>{$value}</div>";
	}

	/**
	 * @since 2.4.2
	 */
	public function get_raw_value( $id ) {
		$comment = get_comment( $id );
		return $comment->comment_date_gmt;
	}
}