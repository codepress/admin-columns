<?php

/**
 * Add support for All in SEO columns
 *
 * @since 2.0
 */
function cpac_load_aioseop_addmycolumns() {
	if ( function_exists('aioseop_addmycolumns') ) {
		aioseop_addmycolumns();
	}
}
add_action( 'cac/columns/default/posts', 'cpac_load_aioseop_addmycolumns' );