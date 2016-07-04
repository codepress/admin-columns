<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Comment_AuthorAvatar extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-author_avatar';
		$this->properties['label'] = __( 'Avatar', 'codepress-admin-columns' );
	}

	public function get_value( $id ) {
		$comment = get_comment( $id );

		return get_avatar( $comment, 80 );
	}

}