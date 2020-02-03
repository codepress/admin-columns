<?php

namespace AC;

trait OpCacheInvalidateTrait {

	/**
	 * Check if the file exists, if opcache is enabled and invalidates the cache
	 *
	 * @param string $script
	 * @param bool   $force
	 */
	protected function opcache_invalidate( $script, $force = false ) {
		if ( function_exists( 'opcache_invalidate' ) && is_file( $script ) ) {
			opcache_invalidate( $script, $force );
		}
	}

}