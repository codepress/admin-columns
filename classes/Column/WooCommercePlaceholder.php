<?php

/**
 * @since 2.4.7
 */
class AC_Column_WooCommercePlaceholder extends AC_Column_Placeholder {

	public function is_valid() {
		return in_array( $this->get_post_type(), array( 'product', 'shop_order', 'shop_coupon' ) );
	}

}