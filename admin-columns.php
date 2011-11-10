<?php
/*
Plugin Name: Admin Columns
Plugin URI: http://www.codepress.nl/
Description: Adds extra admin columns 
Author: Tobias Schutter
Version: 0.1
Author URI: http://www.codepress.nl

Copyright 2010-2011  Codepress  info@codepress.nl

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License version 2 as published by
the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define( 'CPAC_VERSION', '0.3' );

// Determine plugin directory
define( 'CPAC_URL', plugin_dir_url(__FILE__) );
define( 'CPAC_PATH', plugin_dir_path(__FILE__) );
define( 'CPAC_BASENAME', plugin_basename( __FILE__ ) );

// Settings
require CPAC_PATH.'admin-columns-settings.php';

?>