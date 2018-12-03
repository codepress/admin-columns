<?php
namespace AC\Check;

use AC;

final class DeprecatedCount implements AC\Registrable {

	const TRANSIENT_COUNT_KEY = 'ac-deprecated-message-count';

	public function register() {
		if ( false === $this->get_count() ) {
			add_action( 'admin_init', array( $this, 'update_deprecated_count' ) );
		}
	}

	public function get_count() {
		return get_transient( self::TRANSIENT_COUNT_KEY );
	}

	public function delete_count() {
		delete_transient( self::TRANSIENT_COUNT_KEY );
	}

	private function update_count( $count ) {
		set_transient( self::TRANSIENT_COUNT_KEY, $count, WEEK_IN_SECONDS );
	}

	public function update_deprecated_count() {
		$deprecated = new AC\Deprecated();

		$actions = $deprecated->get_deprecated_actions();
		$filters = $deprecated->get_deprecated_filters();

		$this->update_count( count( $actions ) + count( $filters ) );
	}

}