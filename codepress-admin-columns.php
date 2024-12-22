<?php
/*
Plugin Name: Admin Columns
Version: 5.0beta
Description: Customize columns on the administration screens for post(types), pages, media, comments, links and users with an easy to use drag-and-drop interface.
Author: AdminColumns.com
Author URI: https://www.admincolumns.com
Plugin URI: https://www.admincolumns.com
Requires PHP: 7.4
Requires at least: 5.9
Text Domain: codepress-admin-columns
Domain Path: /languages
License: GPL v3

Admin Columns Plugin
Copyright (C) 2011-2023, Admin Columns - info@admincolumns.com
This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program. If not, see <http://www.gnu.org/licenses/>.
*/

// TODO David
// make abstract Plugin class or interface
// make AdminColumnsPro plugin
// make AdminColumns plugin
// decouple get_plugins from that and put that in on construct as a repo for plugin data
// when a location is required, just use plugin instead to resolve unless to much
// plugin should only contain basics like name, version, basename, url and directory. The rest should be deducted

use AC\Loader;
use AC\Vendor\DI\ContainerBuilder;

if ( ! defined('ABSPATH')) {
    exit;
}

if ( ! is_admin()) {
    return;
}

define('AC_FILE', __FILE__);
define('AC_VERSION', '5.0beta');

add_action('after_setup_theme', function () {
    require __DIR__ . '/vendor/autoload.php';
    require __DIR__ . '/api.php';

    if ( ! defined('ACP_VERSION')) {
        $container = (new ContainerBuilder())
            ->addDefinitions(require __DIR__ . '/settings/container-definitions.php')
            ->build();

        new Loader($container);
    }
}, 1);

add_action('after_setup_theme', function () {
    /**
     * For loading external resources, e.g. column settings.
     * Can be called from plugins and themes.
     */
    do_action('ac/ready');
}, 2);