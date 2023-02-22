<?php
declare( strict_types=1 );

namespace AC\ListScreenRepository\Filter;

use AC\ListScreenCollection;
use AC\ListScreenRepository\Filter;
use AC\ListScreenRepository\ListScreenPermissionTrait;
use WP_User;

class ExcludeAdmin implements Filter {

	use ListScreenPermissionTrait;

	private $user;

	public function __construct( WP_User $user ) {
		$this->user = $user;
	}

	public function filter( ListScreenCollection $list_screens ): ListScreenCollection {
		$collection = new ListScreenCollection();

		foreach ( $list_screens as $list_screen ) {
			if ( $this->user_can_view_list_screen( $list_screen, $this->user, false ) ) {
				$collection->add( $list_screen );
			}
		}

		return $collection;
	}

}