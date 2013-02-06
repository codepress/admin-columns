<?php
/*

Plugin Name: 		Admin Columns addon - Multiple Columns
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

define( 'CAC_MC_VERSION', 	 	'0.1' );
define( 'CAC_MC_TEXTDOMAIN', 	'cac-addon-multiple-columns' );
define( 'CAC_MC_URL', 			plugins_url( '', __FILE__ ) );
define( 'CAC_MC_DIR', 			plugin_dir_path( __FILE__ ) );

// only run plugin in the admin interface
if ( !is_admin() )
	return false;

/**
 * Addon class
 *
 * @since 0.1
 *
 */
class CAC_Addon_Multiple_Columns {	

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 */
	function __construct() {
	
		// translations
		load_plugin_textdomain( CAC_MC_TEXTDOMAIN, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		
		// styling & scripts
		add_action( 'admin_enqueue_scripts' , array( $this, 'scripts') );
		
		// add column properties
		add_filter( 'cpac_column_properties', array( $this, 'add_sortable_column_types' ) );
		
		// add remove button
		add_action( 'cpac_after_column_settings' , array( $this, 'add_remove_button') );
		
		// add buttons
		add_action( 'cpac_column_footer' , array( $this, 'footer_buttons') );
	}	
	
	/**
	 * Register scripts
	 *
	 * @since 0.1
	 */
	public function scripts() {
	
		if ( isset( $_REQUEST['page'] ) && 'codepress-admin-columns' == $_REQUEST['page'] ) {
		
			wp_enqueue_style( 'cac-mc-multiple_fields-css', CAC_MC_URL . '/assets/css/multiple-fields.css', array(), CAC_MC_VERSION, 'all' );
			wp_enqueue_script( 'cac-mc-multiple_fields-js', CAC_MC_URL . '/assets/js/multiple-fields.js', array( 'jquery', 'dashboard', 'jquery-ui-sortable', 'cpac-admin-columns' ), CAC_MC_VERSION, 'all' );
			
		}
	}
	
	/**
	 * Add sortable column types
	 *
	 * @since 0.1
	 */
	function add_sortable_column_types( $properties ) {
		
		$include_types = apply_filters( 'cpac_sortable_column_types', array( 
			'column-meta', 
			'column-taxonomy',
			'column-comment-count',
			'column-author-name',
		));
		
		// only add property is_cloneable when it has not yet been set.
		if ( ! isset( $properties['is_cloneable'] ) ) {		
			$properties['is_cloneable'] = in_array( $properties['type'], $include_types ) ? true : false;
		}
		
		return $properties;	
	}
	
	/**
	 * Footer buttons
	 *
	 * @since 0.1
	 */
	function add_remove_button( $column ) {
		
		if ( ! ( $column->properties->is_cloneable || ( isset( $column->options->clone ) && $column->options->clone ) ) )
			return false;
		
		?>
		<tr class="column_action">
			<td colspan="2">
				<?php if ( null === $column->properties->clone ) : ?>
					<p class="remove-description description"><?php _e( 'This field can not be removed', CPAC_TEXTDOMAIN ); ?></p>
				<?php else : ?>
					<p><a href="javascript:;" class="remove-button"><?php _e( 'Remove');?></a></p>
				<?php endif; ?>
			</td>
		</tr>	
		<?php
	}
	 
	/**
	 * Footer buttons
	 *
	 * @since 0.1
	 */
	function footer_buttons( $storage_model ) {
		
		$buttons = array();
		
		foreach ( $storage_model->get_registered_columns() as $column ) {
			
			if ( $column->properties->is_cloneable ) {				
				$buttons[ $column->properties->type ] = array( 
					'type'	=> $column->properties->type,				
					'label'	=> $column->properties->label
				);
			}
		}
		
		?>
		
		<div class="button-container">		
		<?php foreach ( $buttons as $button ) : ?>
			<a href="javascript:;" data-type="<?php echo $button['type']; ?>" class="clone-button button">+ <?php _e( 'Add', CPAC_TEXTDOMAIN );?> <?php echo $button['label']; ?></a><br/>
		<?php endforeach; ?>		
		</div>
		
		<?php
	}
	
	
}

/**
 * Init Class CAC_Addon_Multiple_Columns
 *
 * @since 1.0.0
 */
new CAC_Addon_Multiple_Columns();