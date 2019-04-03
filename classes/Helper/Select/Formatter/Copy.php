<?php

namespace AC\Helper\Select\Formatter;

use AC\Helper\Select;

use WP_Post;

class Copy extends Select\Formatter {

	public function __construct( Select\Entities $entities, Select\Value $value = null ) {
		if ( null === $value ) {
			$value = new Select\Value\Copy();
		}

		parent::__construct( $entities, $value );
	}

	/**
	 * @param WP_Post $post
	 *
	 * @return string
	 */
	public function get_label( $test ) {
		return $test;
	}

}