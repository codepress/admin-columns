<?php

use AC\Column;
use AC\Exception\HookTimingException;
use AC\ListScreen;
use AC\ListScreenCollection;
use AC\ListScreenRepository\Storage;
use AC\Plugin\Version;
use AC\Registry;
use AC\Type\ColumnId;
use AC\Type\ListScreenId;
use AC\Type\TableId;
use AC\Type\Url;

/**
 * For usage @see https://docs.admincolumns.com/article/57-code-snippets
 * List of available template functions:
 * @method ac_get_list_screens()
 * @method ac_get_list_screen()
 * @method ac_get_column()
 * @method ac_get_columns()
 * @method AC()
 */

if ( ! function_exists('ac_get_list_screen')) {
    /**
     * Return the list table configuration, such as its ID, columns, and settings.
     * For usage @see https://docs.admincolumns.com/article/57-code-snippets
     */
    function ac_get_list_screen(string $id): ?ListScreen
    {
        if ( ! did_action('wp_loaded')) {
            throw HookTimingException::called_too_early('wp_loaded');
        }

        $storage = Registry::get(Storage::class);

        if ( ! $storage instanceof Storage) {
            return null;
        }

        try {
            return $storage->find(new ListScreenId($id));
        } catch (Throwable $e) {
            return null;
        }
    }
}

if ( ! function_exists('ac_get_list_screens')) {
    /**
     * Returns a collection of all list table configurations for a specific table.
     * For usage @see https://docs.admincolumns.com/article/57-code-snippets
     */
    function ac_get_list_screens(string $table_id): ListScreenCollection
    {
        if ( ! did_action('wp_loaded')) {
            throw HookTimingException::called_too_early('wp_loaded');
        }

        $storage = Registry::get(Storage::class);

        if ( ! $storage instanceof Storage) {
            return new ListScreenCollection();
        }

        try {
            return $storage->find_all_by_table_id(new TableId($table_id));
        } catch (Throwable $e) {
            return new ListScreenCollection();
        }
    }
}

if ( ! function_exists('ac_get_column')) {
    /**
     * Returns a single column by its name and list screen ID.
     * For usage @see https://docs.admincolumns.com/article/57-code-snippets
     */
    function ac_get_column(string $column_name, string $list_screen_id): ?Column
    {
        if ( ! did_action('wp_loaded')) {
            throw HookTimingException::called_too_early('wp_loaded');
        }

        $storage = Registry::get(Storage::class);

        if ( ! $storage instanceof Storage) {
            return null;
        }

        try {
            $list_screen = $storage->find(new ListScreenId($list_screen_id));
        } catch (Throwable $e) {
            return null;
        }

        if ( ! $list_screen) {
            return null;
        }

        try {
            return $list_screen->get_column(new ColumnId($column_name));
        } catch (Throwable $e) {
            return null;
        }
    }
}

if ( ! function_exists('ac_get_columns')) {
    /**
     * Returns all columns for a specific list table.
     * For usage @see https://docs.admincolumns.com/article/57-code-snippets
     * @return Column[]
     */
    function ac_get_columns(string $list_screen_id): array
    {
        if ( ! did_action('wp_loaded')) {
            throw HookTimingException::called_too_early('wp_loaded');
        }

        $storage = Registry::get(Storage::class);

        if ( ! $storage instanceof Storage) {
            return [];
        }

        try {
            $list_screen = $storage->find(new ListScreenId($list_screen_id));
        } catch (Throwable $e) {
            return [];
        }

        if ( ! $list_screen) {
            return [];
        }

        return iterator_to_array($list_screen->get_columns());
    }
}

if ( ! function_exists('AC')) {
    /**
     * returns thw AC\AdminColumn object. Contains the plugin version and directory path.
     */
    function AC(): AC\AdminColumns
    {
        static $ac = null;

        if ($ac === null) {
            $ac = new AC\AdminColumns(AC_FILE, new Version(AC_VERSION));
        }

        return $ac;
    }
}

if ( ! function_exists('ac_is_pro_active')) {
    function ac_is_pro_active(): bool
    {
        return defined('ACP_VERSION');
    }
}

function ac_get_admin_url(?string $slug = null): string
{
    _deprecated_function(__METHOD__, '4.5', 'Url\Editor');

    return (new Url\Editor($slug))->get_url();
}

function ac_get_admin_network_url(?string $slug = null): string
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
    ?string $utm_content = null,
    ?string $utm_campaign = null
): string {
    _deprecated_function(__METHOD__, '6.0', 'AC\Type\UrlUtmTags()');

    return (new Url\UtmTags(new Url\Site($path), $utm_medium, $utm_content, $utm_campaign))->get_url();
}

function ac_get_site_documentation_url(?string $path = null): string
{
    _deprecated_function(__METHOD__, '6.0', 'AC\Type\Url\Documentation()');

    return (new Url\Documentation($path))->get_url();
}

function ac_get_site_url(?string $path = null): string
{
    _deprecated_function(__METHOD__, '6.0', 'AC\Type\Url\Site()');

    return (new Url\Site($path))->get_url();
}

function ac_get_url(string $relative_file_path): string
{
    _deprecated_function(__FUNCTION__, '7.0.10');

    return '';
}

if ( ! function_exists('ac_load_columns')) {
    function ac_load_columns(): void
    {
        _deprecated_function(__METHOD__, '4.1');
    }
}

if ( ! function_exists('ac_helper')) {
    /**
     * @deprecated 7.0.10 Use Helper\X::create() instead.
     */
    function ac_helper(): AC\Helper
    {
        _deprecated_function(__FUNCTION__, '7.0.10', 'Helper\X::create()');

        return new AC\Helper();
    }
}

if ( ! function_exists('ac_format_date')) {
    function ac_format_date(string $format, ?int $timestamp = null, ?DateTimeZone $timezone = null): ?string
    {
        _deprecated_function(__METHOD__, '7.0.10', 'wp_date()');

        return wp_date($format, $timestamp, $timezone ?? new DateTimeZone('UTC')) ?: null;
    }
}






















