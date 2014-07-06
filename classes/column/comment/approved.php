<?php
/**
 * CPAC_Column_Comment_Approved
 *
 * @since 2.0
 */
class CPAC_Column_Comment_Approved extends CPAC_Column {

	/**
	 * @see CPAC_Column::init()
	 * @since 2.2.1
	 */
	public function init() {

		parent::init();

		// Properties
		$this->properties['type']	 = 'column-approved';
		$this->properties['label']	 = __( 'Approved', 'cpac' );
	}

	/**
	 * @see CPAC_Column::get_value()
	 * @since 2.0
	 */
	function get_value( $id ) {

	$comment = get_comment( $id );

		$value = $this->get_asset_image( 'no.png' );
		if ( $comment->comment_approved ) {

			$value = $this->get_asset_image( 'checkmark.png' );
		}

		return $value;
	}
}