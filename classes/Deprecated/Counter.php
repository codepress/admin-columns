<?php

namespace AC\Deprecated;

class Counter {

	const KEY = 'ac-deprecated-message-count';

	public function get() {
		return get_transient( self::KEY );
	}

	public function delete() {
		delete_transient( self::KEY );
	}

	public function update( $count ) {
		set_transient( self::KEY, $count, WEEK_IN_SECONDS );
	}

}