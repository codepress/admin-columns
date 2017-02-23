<?php

/**
 * @since 2.0
 */
class AC_Column_Comment_Comment extends AC_Column {

	public function __construct() {
		$this->set_original( true );
		$this->set_type( 'comment' );
	}

}