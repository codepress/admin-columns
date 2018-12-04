<?php
namespace AC\Check;

use AC;
use AC\Deprecated\Counter;

final class DeprecatedCount implements AC\Registrable {

	public function register() {
		add_action( 'admin_init', array( $this, 'update_deprecated_count' ) );
	}

	public function update_deprecated_count() {
		$counter = new Counter();

		if ( false !== $counter->get() ) {
			return;
		}

		$hooks = new AC\Deprecated\Hooks();

		$counter->update( $hooks->get_deprecated_count() );
	}

}