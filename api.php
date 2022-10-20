<?php

use AC\EncodedListScreenDataFactory;
use AC\Helper;
use AC\ListScreen;
use AC\ListScreenCollection;
use AC\Type\ListScreenId;
use AC\Type\Url;

/**
 * @since 3.0
 */
// TODO David remove the singleton, make a factory with a shared instance for AC this should be no issue
if ( ! function_exists( 'AC' ) ) {
	function AC(): AC\AdminColumns {
		static $ac = null;

		if ( $ac === null ) {
			$ac = AC\AdminColumns::instance();
		}

		return $ac;
	}
}

/**
 * We check the defined const because it is available before AC::__construct() runs.
 * @return bool
 */
// TODO Stefan this should not be an api function?
function ac_is_pro_active() {
	return defined( 'ACP_FILE' );
}

/**
 * @param string $path
 *
 * @return string
 */
// TODO Stefan this should not be an api function?
function ac_get_site_url( $path = null ) {
	return ( new Url\Site( $path ) )->get_url();
}

/**
 * @param string|null $path
 *
 * @return string
 */
// TODO Stefan this should not be an api function?
function ac_get_site_documentation_url( $path = null ) {
	return ( new Url\Documentation( $path ) )->get_url();
}

/**
 * @param string $path
 * @param string $utm_medium
 * @param string $utm_content
 * @param bool   $utm_campaign
 *
 * @return string
 */
// TODO Stefan this should not be an api function?
function ac_get_site_utm_url( $path, $utm_medium, $utm_content = null, $utm_campaign = null ) {
	return ( new Url\UtmTags( new Url\Site( $path ), $utm_medium, $utm_content, $utm_campaign ) )->get_url();
}

/**
 * @return string
 */
// TODO Stefan this should not be an api function?
function ac_get_twitter_handle() {
	return 'admincolumns';
}

/**
 * Simple helper methods for AC/Column objects
 * @since 3.0
 */
if ( ! function_exists( 'ac_helper' ) ) {
	function ac_helper() {
		return new AC\Helper();
	}
}

/**
 * Manually set the columns for a list screen
 * This overrides the database settings and thus renders the settings screen for this list screen useless
 * If you like to register a column of your own please have a look at our documentation.
 * We also have a free start-kit available, which contains all the necessary files.
 * Documentation: https://www.admincolumns.com/documentation/guides/creating-new-column-type/
 * Starter-kit: https://github.com/codepress/ac-column-template/
 *
 * @param array $data
 *
 * @deprecated 4.1
 * @since      4.0.0
 */
if ( ! function_exists( 'ac_load_columns' ) ) {
	function ac_load_columns( array $data ) {
		$factory = new EncodedListScreenDataFactory();
		$factory->create()->add( $data );
	}
}

/**
 * Convert site_url() to [cpac_site_url] and back for easy migration
 *
 * @param string $label
 * @param string $action
 *
 * @return string
 */
// TODO Stefan can this be removed from the api?
function ac_convert_site_url( $label, $action = 'encode' ) {
	$input = [ site_url(), '[cpac_site_url]' ];

	if ( 'decode' === $action ) {
		$input = array_reverse( $input );
	}

	return stripslashes( str_replace( $input[0], $input[1], trim( $label ) ) );
}

/**
 * @param string $id Layout ID e.g. ac5de58e04a75b0
 *
 * @return ListScreen|null
 * @since 4.0.0
 */
if ( ! function_exists( 'ac_get_list_screen' ) ) {
	function ac_get_list_screen( $id ) {
		return AC()->get_storage()->find( new ListScreenId( $id ) );
	}
}

/**
 * Usage: Load after or within the 'wp_loaded' action hook.
 *
 * @param string $key e.g. post, page, wp-users, wp-media, wp-comments
 *
 * @return ListScreenCollection
 * @since 4.0.0
 */
if ( ! function_exists( 'ac_get_list_screens' ) ) {
	function ac_get_list_screens( $key ) {
		return AC()->get_storage()->find_all( [ 'key' => $key ] );
	}
}

/**
 * Usage: Load after or within the 'wp_loaded' action hook.
 *
 * @param string $column_name
 * @param string $list_screen_id
 *
 * @return AC\Column|null
 * @since 4.2
 */
if ( ! function_exists( 'ac_get_column' ) ) {
	function ac_get_column( $column_name, $list_screen_id ) {
		try {
			$list_id = new ListScreenId( $list_screen_id );
		} catch ( Exception $e ) {
			return null;
		}

		$list_screen = AC()->get_storage()->find( $list_id );

		if ( ! $list_screen ) {
			return null;
		}

		$column = $list_screen->get_column_by_name( $column_name );

		if ( ! $column ) {
			return null;
		}

		return $column;
	}
}

/**
 * Usage: Load after or within the 'wp_loaded' action hook.
 *
 * @param string $list_screen_id
 *
 * @return AC\Column[]
 * @since 4.2
 */
if ( ! function_exists( 'ac_get_columns' ) ) {
	function ac_get_columns( $list_screen_id ) {
		try {
			$list_id = new ListScreenId( $list_screen_id );
		} catch ( Exception $e ) {
			return [];
		}

		$list_screen = AC()->get_storage()->find( $list_id );

		if ( ! $list_screen ) {
			return [];
		}

		return $list_screen->get_columns();
	}
}

/**
 * @param                   $format
 * @param null              $timestamp
 * @param DateTimeZone|null $timezone
 *
 * @return false|string
 */
if ( ! function_exists( 'ac_format_date' ) ) {
	function ac_format_date( $format, $timestamp = null, DateTimeZone $timezone = null ) {
		return ( new Helper\Date )->format_date( $format, $timestamp, $timezone );
	}
}

/**
 * @param string|null $slug
 *
 * @return string
 * @deprecated 4.5
 */
function ac_get_admin_url( $slug = null ) {
	_deprecated_function( __METHOD__, '4.5', 'Url\Editor' );

	return ( new Url\Editor( $slug ) )->get_url();
}

/**
 * @param string|null $slug
 *
 * @return string
 * @deprecated 4.5
 */
function ac_get_admin_network_url( $slug = null ) {
	_deprecated_function( __METHOD__, '4.5', 'Url\EditorNetwork' );

	return ( new Url\EditorNetwork( $slug ) )->get_url();
}

/**
 * @param array|string $list_screen_keys
 * @param array        $column_data
 *
 * @deprecated 4.0.0
 * @since      2.2
 */
function ac_register_columns( $list_screen_keys, $column_data ) {
	foreach ( (array) $list_screen_keys as $key ) {
		ac_load_columns( [ $key => $column_data ] );
	}
}