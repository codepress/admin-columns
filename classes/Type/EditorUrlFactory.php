<?php

namespace AC\Type;

use AC\Type\Url\Editor;
use AC\Type\Url\EditorNetwork;

class EditorUrlFactory {

	/**
	 * @param string|null $slug
	 *
	 * @return Url
	 */
	public static function create( $slug = null ) {
		return is_multisite() && is_network_admin()
			? new EditorNetwork( $slug )
			: new Editor( $slug );
	}

}