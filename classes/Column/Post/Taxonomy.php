<?php

namespace AC\Column\Post;

use AC\Column;

/**
 * @since 3.0
 */
class Taxonomy extends Column\Taxonomy {

	/**
	 * @return bool True when post type has associated taxonomies
	 */
	public function is_valid() {
		return get_object_taxonomies( $this->get_post_type() ) ? true : false;
	}

}