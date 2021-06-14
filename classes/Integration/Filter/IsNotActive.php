<?php

namespace AC\Integration\Filter;

class IsNotActive extends IsActive {

	public function __construct( $is_multisite, $is_network_admin ) {
		parent::__construct( $is_multisite, $is_network_admin, false );
	}

}