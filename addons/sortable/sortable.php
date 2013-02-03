<?php
/*

Plugin Name: 		Admin Columns Addon - Sortable Columns
Version: 			0.1
Description: 		Add support for multiple columns.
Author: 			Codepress
Author URI: 		http://www.admincolumns.com
Plugin URI: 		http://www.admincolumns.com
Text Domain: 		cpac-multiple-columns
Domain Path: 		/languages
License:			GPLv2

Copyright 2013  Codepress  info@codepress.nl

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

define( 'CAC_SC_VERSION', 	 	'0.1' );
define( 'CAC_SC_TEXTDOMAIN', 	'cac-addon-sortable-columns' );
define( 'CAC_SC_URL', 			plugins_url( '', __FILE__ ) );
define( 'CAC_SC_DIR', 			plugin_dir_path( __FILE__ ) );

// only run plugin in the admin interface
if ( ! is_admin() )
	return false;
	
// Includes
include_once 'classes/post.php';	
	
/**
 * Addon class
 *
 * @since 0.1
 *
 */
class CAC_Addon_Sortable {	
	
	/**
	 * Constructor
	 *
	 * @since 0.1
	 */
	private $sortable_columns;
	
	/**
	 * Constructor
	 *
	 * @since 0.1
	 */
	function __construct() {

		load_plugin_textdomain( CAC_SC_TEXTDOMAIN, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );	
		
		// define which column types are sortable
		$this->sortable_columns = array(
			'column-meta', 
			'column-excerpt' 
		);
		
		// add column properties
		add_filter( 'cpac_column_properties', array( $this, 'set_column_properties' ) );
		
		// add column options
		add_filter( 'cpac_column_default_options', array( $this, 'set_column_default_options' ) );
		
		// add setting field
		add_action( 'cpac_after_column_settings', array( $this, 'add_settings_field' ), 9 );

	}
	
	/**
	 * Set column properties
	 *
	 * @since 0.1
	 */
	function set_column_properties( $properties ) {
		
		$properties['is_sortable'] = in_array( $properties['type'], $this->sortable_columns ) ? true : false;
		
		return $properties;	
	}
	
	/**
	 * Set column options
	 *
	 * @since 0.1
	 */
	function set_column_default_options( $options ) {
		
		$options['sort'] = 'off';
		
		return $options;	
	}
	
	/**
	 * Settings
	 *
	 * @since 2.0.0
	 */
	function add_settings_field( $column ) {		
		
		if ( ! $column->properties->is_sortable )
			return false;
		
		$sort = isset( $column->options->sort ) ? $column->options->sort : '';
		
		?>
		
		<tr class="column_sorting">			
			<?php $column->label_view( __( 'Enable sorting?', CPAC_TEXTDOMAIN ), __( 'This will make the column support sorting.', CPAC_TEXTDOMAIN ), 'sorting' ); ?>	
			<td class="input">
				<label for="<?php $column->attr_id( 'sort' ); ?>-on">
					<input type="radio" value="on" name="<?php $column->attr_name( 'sort' ); ?>" id="<?php $column->attr_id( 'sort' ); ?>-on"<?php checked( $column->options->sort, 'on' ); ?>>
					<?php _e( 'Yes'); ?>
				</label>
				<label for="<?php $column->attr_id( 'sort' ); ?>-off">
					<input type="radio" value="" name="<?php $column->attr_name( 'sort' ); ?>" id="<?php $column->attr_id( 'sort' ); ?>-off"<?php checked( $column->options->sort, '' ); ?><?php checked( $column->options->sort, 'off' ); ?>>
					<?php _e( 'No'); ?>
				</label>
			</td>
		</tr>
		
	<?php
	}
}

new CAC_Addon_Sortable;