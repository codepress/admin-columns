<?php
declare( strict_types=1 );

namespace AC\Storage;

use AC\Preferences\Site;

class TableListOrder extends Site {

	public function __construct( int $user_id ) {
		parent::__construct( 'list_order', $user_id );
	}
}