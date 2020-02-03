<?php

namespace AC\ListScreenRepository\FilterStrategy;

use AC\ListScreenCollection;
use AC\ListScreenRepository\FilterStrategy;
use AC\PermissionChecker;

class ByPermission implements FilterStrategy {

	/** @var PermissionChecker */
	protected $permission_checker;

	public function __construct( PermissionChecker $permission_checker ) {
		$this->permission_checker = $permission_checker;
	}

	public function filter( ListScreenCollection $list_screens ) {
		$filtered = new ListScreenCollection();

		foreach ( $list_screens as $list_screen ) {
			if ( $this->permission_checker->is_valid( $list_screen ) ) {
				$filtered->push( $list_screen );
			}
		}

		return $filtered;
	}

}