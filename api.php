<?php

use AC\ListScreen;
use AC\ListScreenTypes;

/**
 * @return AC\AdminColumns
 * @since 3.0
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
 * @param string $path
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

function ac_get_list_screen_types() {
	if ( ! did_action( 'init' ) ) {
		wc_doing_it_wrong( __FUNCTION__, 'ac_get_list_screen_types should not be called before post types are registered', 'NEWVERSION' );
	}

	return ListScreenTypes::instance()->get_list_screens();
}

/**
 * Url with utm tags
 *
 * @param string $path
 * @param string $utm_medium
 * @param string $utm_content
 * @param bool   $utm_campaign
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
 * @return string
 */
function ac_get_twitter_handle() {
	return 'admincolumns';
}

/**
 * Simple helper methods for AC_Column objects
 * @since 3.0
 */
function ac_helper() {
	return new AC\Helper();
}

/**
 * Manually set the columns for a list screen
 * This overrides the database settings and thus renders the settings screen for this list screen useless
 * If you like to register a column of your own please have a look at our documentation.
 * We also have a free start-kit available, which contains all the necessary files.
 * Documentation: https://www.admincolumns.com/documentation/developer-docs/creating-new-column-type/
 * Starter-kit: https://github.com/codepress/ac-column-template/
 *
 * @param array|string $list_screen_keys
 * @param array        $column_data
 *
 * @since 2.2
 */
function ac_register_columns( $list_screen_keys, $column_data ) {
	AC()->api()->load_columndata( $list_screen_keys, $column_data );
}

/**
 * @param string $slug Page slug
 *
 * @return string
 */
function ac_get_admin_url( $slug = null ) {
	if ( null === $slug ) {
		$slug = 'columns';
	}

	return AC()->admin()->get_url( $slug );
}

/**
 * @return int
 */
function ac_get_lowest_price() {
	return 49;
}

/**
 * @param ListScreen   $list_screen
 * @param WP_User|null $user
 *
 * @return bool
 */
function ac_user_has_permission_list_screen( ListScreen $list_screen, WP_User $user = null ) {
	if ( null === $user ) {
		$user = wp_get_current_user();
	}

	$roles = ! empty( $list_screen->get_preferences()['roles'] ) ? $list_screen->get_preferences()['roles'] : [];
	$users = ! empty( $list_screen->get_preferences()['users'] ) ? $list_screen->get_preferences()['users'] : [];

	if ( empty( $users ) && empty( $roles ) ) {
		return true;
	}

	foreach ( $roles as $role ) {
		if ( $user->has_cap( $role ) ) {
			return true;
		}
	}

	if ( in_array( $user->ID, $users, true ) ) {
		return true;

	}

	return false;
}

/**
 * Convert site_url() to [cpac_site_url] and back for easy migration
 *
 * @param string $label
 * @param string $action
 *
 * @return string
 */
function ac_convert_site_url( $label, $action = 'encode' ) {
	$input = array( site_url(), '[cpac_site_url]' );

	if ( 'decode' == $action ) {
		$input = array_reverse( $input );
	}

	return stripslashes( str_replace( $input[0], $input[1], trim( $label ) ) );
}