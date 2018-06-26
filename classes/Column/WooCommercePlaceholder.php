<?php

namespace AC\Column;

use AC\Column;

/**
 * @since 2.4.7
 */
class WooCommercePlaceholder extends Column\Placeholder {

	public function is_valid() {
		return in_array( $this->get_post_type(), array( 'product', 'shop_order', 'shop_coupon' ) );
	}

}