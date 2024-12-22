<?php

use AC\Column;
use AC\Container;
use AC\ListScreen;
use AC\ListScreenCollection;
use AC\Type\ColumnId;
use AC\Type\ListKey;
use AC\Type\ListScreenId;
use AC\Type\Url;

function ac_get_url(string $relative_file_path): string
{
    return Container::get_location()->with_suffix($relative_file_path)->get_url();
}

// TODO David do we need this even?
if ( ! function_exists('AC')) {
    function AC(): AC\AdminColumns
    {
        static $ac = null;

        if ($ac === null) {
            $ac = new AC\AdminColumns();
        }

        return $ac;
    }
}

if ( ! function_exists('ac_helper')) {
    function ac_helper(): AC\Helper
    {
        return new AC\Helper();
    }
}

/**
 * For usage @see https://docs.admincolumns.com/article/64-template-functions
 */
if ( ! function_exists('ac_get_list_screen')) {
    function ac_get_list_screen(string $id): ?ListScreen
    {
        if ( ! did_action('wp_loaded')) {
            throw new RuntimeException("Call after the `wp_loaded` hook.");
        }

        return Container::get_storage()->find(new ListScreenId($id));
    }
}

/**
 * For usage @see https://docs.admincolumns.com/article/64-template-functions
 */
if ( ! function_exists('ac_get_list_screens')) {
    function ac_get_list_screens(string $key): ListScreenCollection
    {
        if ( ! did_action('wp_loaded')) {
            throw new RuntimeException("Call after the `wp_loaded` hook.");
        }

        return Container::get_storage()->find_all_by_key(new ListKey($key));
    }
}

/**
 * For usage @see https://docs.admincolumns.com/article/64-template-functions
 */
if ( ! function_exists('ac_get_column')) {
    function ac_get_column(string $column_name, string $list_screen_id): ?Column
    {
        if ( ! did_action('wp_loaded')) {
            throw new RuntimeException("Call after the `wp_loaded` hook.");
        }

        try {
            $list_id = new ListScreenId($list_screen_id);
        } catch (Exception $e) {
            return null;
        }

        $list_screen = Container::get_storage()->find($list_id);

        if ( ! $list_screen) {
            return null;
        }

        return $list_screen->get_column(new ColumnId($column_name));
    }
}

/**
 * For usage @see https://docs.admincolumns.com/article/64-template-functions
 */
if ( ! function_exists('ac_get_columns')) {
    /**
     * @return Column[]
     */
    function ac_get_columns(string $list_screen_id): array
    {
        if ( ! did_action('wp_loaded')) {
            throw new RuntimeException("Call after the `wp_loaded` hook.");
        }

        try {
            $list_id = new ListScreenId($list_screen_id);
        } catch (Exception $e) {
            return [];
        }

        $list_screen = Container::get_storage()->find($list_id);

        if ( ! $list_screen) {
            return [];
        }

        return iterator_to_array($list_screen->get_columns());
    }
}

if ( ! function_exists('ac_format_date')) {
    function ac_format_date(string $format, int $timestamp = null, DateTimeZone $timezone = null): ?string
    {
        return wp_date($format, $timestamp, $timezone) ?: null;
    }
}

function ac_get_admin_url(string $slug = null): string
{
    _deprecated_function(__METHOD__, '4.5', 'Url\Editor');

    return (new Url\Editor($slug))->get_url();
}

function ac_get_admin_network_url(string $slug = null): string
{
    _deprecated_function(__METHOD__, '4.5', 'Url\EditorNetwork');

    return (new Url\EditorNetwork($slug))->get_url();
}

function ac_register_columns(): void
{
    _deprecated_function(__METHOD__, '4.0');
}

function ac_get_site_utm_url(
    string $path,
    string $utm_medium,
    string $utm_content = null,
    string $utm_campaign = null
): string {
    _deprecated_function(__METHOD__, '6.0', 'AC\Type\UrlUtmTags()');

    return (new Url\UtmTags(new Url\Site($path), $utm_medium, $utm_content, $utm_campaign))->get_url();
}

function ac_get_site_documentation_url(string $path = null): string
{
    _deprecated_function(__METHOD__, '6.0', 'AC\Type\Url\Documentation()');

    return (new Url\Documentation($path))->get_url();
}

function ac_get_site_url(string $path = null): string
{
    _deprecated_function(__METHOD__, '6.0', 'AC\Type\Url\Site()');

    return (new Url\Site($path))->get_url();
}

if ( ! function_exists('ac_load_columns')) {
    function ac_load_columns(): void
    {
        _deprecated_function(__METHOD__, '4.1');
    }
}

function ac_is_pro_active(): bool
{
    _deprecated_function(__METHOD__, '6.0');

    return defined('ACP_FILE');
}























