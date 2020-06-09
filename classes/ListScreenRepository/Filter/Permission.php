<?php

namespace AC\ListScreenRepository\Filter;

use AC\ListScreenCollection;
use AC\ListScreenRepository\Filter;
use AC\PermissionChecker;

class Permission implements Filter {

	/**
	 * @var PermissionChecker
	 */
	protected $permission_checker;

	public function __construct( PermissionChecker $permission_checker ) {
		$this->permission_checker = $permission_checker;
	}

	public function filter( ListScreenCollection $list_screens ) {
		foreach ( clone $list_screens as $list_screen ) {
			if ( ! $this->permission_checker->is_valid( wp_get_current_user(), $list_screen ) ) {
				$list_screens->remove( $list_screen );
			}
		}

		return $list_screens;
	}

}