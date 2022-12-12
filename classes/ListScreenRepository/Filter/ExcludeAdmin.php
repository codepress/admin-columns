<?php
declare( strict_types=1 );

namespace AC\ListScreenRepository\Filter;

use AC\ListScreenCollection;
use AC\ListScreenRepository\Filter;
use WP_User;

class ExcludeAdmin implements Filter {

	private $user;

	public function __construct( WP_User $user ) {
		$this->user = $user;
	}

	public function filter( ListScreenCollection $list_screens ): ListScreenCollection {
		// TODO Tobias
		$user = $this->user;

		return $list_screens;
	}

}