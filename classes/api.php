<?php

/**
 * Admin Columns - API
 *
 * @since 2.0.0
 * @todo: build API
 */


/**
 * Flush cache of storage model
 *
 * @since 2.0.0
 */
function cac_flush_cache_manual( $cpac ) {
	if ( isset( $_REQUEST['cpac_flush'] ) ) {

		// flush this transient so new custom columns get added.
		foreach ( $cpac->storage_models as $storage_model ) {
			$storage_model->flush_cache();
		}
	}
}
add_action( 'cac/loaded', 'cac_flush_cache_manual' );