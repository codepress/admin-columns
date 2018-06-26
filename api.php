<?php

/**
 * @since 3.0
 * @return AC\AdminColumns
 */
function AC() {
	return AC\AdminColumns::instance();
}

/**
 * @return bool True when Admin Columns Pro plugin is activated.
 */
function ac_is_pro_active() {
	return function_exists( 'ACP' );
}

/**
 * Get the url where the Admin Columns website is hosted
 *
 * @return string
 */
function ac_get_site_url( $path = '' ) {
	$url = 'https://www.admincolumns.com';

	if ( ! empty( $path ) ) {
		$url .= '/' . trim( $path, '/' ) . '/';
	}

	return $url;
}

/**
 * Url with utm tags
 *
 * @param string $path
 * @param string $utm_medium
 * @param string $utm_content
 *
 * @return string
 */
function ac_get_site_utm_url( $path, $utm_medium, $utm_content = null, $utm_campaign = false ) {
	$url = ac_get_site_url( $path );

	if ( ! $utm_campaign ) {
		$utm_campaign = 'plugin-installation';
	}

	$args = array(
		// Referrer: plugin
		'utm_source'   => 'plugin-installation',

		// Specific promotions or sales
		'utm_campaign' => $utm_campaign,

		// Marketing medium: banner, documentation or email
		'utm_medium'   => $utm_medium,

		// Used for differentiation of medium
		'utm_content'  => $utm_content,
	);

	$args = array_map( 'sanitize_key', array_filter( $args ) );

	return add_query_arg( $args, $url );
}

/**
 * Admin Columns Twitter username
 *
 * @return string
 */
function ac_get_twitter_handle() {
	return 'admincolumns';
}

/**
 * Simple helper methods for AC_Column objects
 *
 * @since 3.0
 */
function ac_helper() {
	return new AC\Helper();
}

/**
 * Manually set the columns for a list screen
 * This overrides the database settings and thus renders the settings screen for this list screen useless
 *
 * If you like to register a column of your own please have a look at our documentation.
 * We also have a free start-kit available, which contains all the necessary files.
 *
 * Documentation: https://www.admincolumns.com/documentation/developer-docs/creating-new-column-type/
 * Starter-kit: https://github.com/codepress/ac-column-template/
 *
 * @since 2.2
 *
 * @param string|array $list_screen_key List screen key or keys
 * @param array        $column_data
 */
function ac_register_columns( $list_screen_keys, $column_data ) {
	AC()->api()->load_columndata( $list_screen_keys, $column_data );
}