<?php
declare( strict_types=1 );

namespace AC\ListScreenRepository;

use AC\ListScreenRepository\Sort\Label;
use AC\ListScreenRepository\Sort\ManualOrder;
use AC\ListScreenRepository\Sort\Nullable;

class OrderByFactory {

	public function create( string $order_by = null ): Sort {
		switch ( $order_by ) {
			case 'manual' :
				return new ManualOrder();
			case 'label' :
				return new Label();
			default:
				return new Nullable();
		}
	}

}