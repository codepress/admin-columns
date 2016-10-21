<?php
defined( 'ABSPATH' ) or die();

/**
 * @since NEWVERSION
 */
class AC_Column_Media_Comments extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'comments';
		$this->properties['hide_label'] = true;
	}

	public function is_valid() {
		return post_type_supports( $this->get_post_type(), 'comments' );
	}

}