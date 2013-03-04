<?php
/**
 * CPAC_Column_Comment_Date_Gmt
 *
 * @since 2.0.0
 */
class CPAC_Column_Comment_Date_Gmt extends CPAC_Column {

	function __construct( $storage_model ) {

		$this->properties['type']	 = 'column-date_gmt';
		$this->properties['label']	 = __( 'Date GMT', 'cpac' );

		parent::__construct( $storage_model );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0.0
	 */
	function get_value( $id ) {

		$comment = get_comment( $id );

		$value = sprintf( __( 'Submitted on <a href="%1$s">%2$s at %3$s</a>' ),
			esc_url( get_comment_link( $id ) ),
			$this->get_date( $comment->comment_date_gmt ),
			$this->get_time( $comment->comment_date_gmt )
		);

		return "<div class='submitted-on'>{$value}</div>";
	}
}