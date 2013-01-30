<?php

class CPAC_Addon_Sorting {

	function __construct() {

		// add js
		//add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
	
		// options
		add_filter( 'cpac_column_default_options', array( $this, 'set_options' ) );
		add_filter( 'cpac_column_default_properties', array( $this, 'set_properties' ) );
		
		// display settings
		add_action( 'cpac_after_column_settings', array( $this, 'settings' ) );
		
		// $this->licenses['sortable']->is_unlocked() ) {}
	}

	/**
	 * Load Admin Scripts
	 *
	 * @since 2.0.0
	 */
	function admin_scripts() {

	}
	
	/**
	 * Set options
	 *
	 * @since 2.0.0
	 */
	function set_options( $options ) {
		$options['sort'] = false;
		
		return $options;
	}
	
	/**
	 * Set properties
	 *
	 * @since 2.0.0
	 */
	function set_properties( $properties ) {		
		$properties['enable_sorting'] = false;
		
		return $properties;
	}
	
	/**
	 * Settings
	 *
	 * @since 2.0.0
	 */
	function settings( $column ) {		
		?>
		<tr class="column_sort">

			<?php $column->label_view( __( 'Enable sorting?', CPAC_TEXTDOMAIN ), __( 'This will make the column support sorting.', CPAC_TEXTDOMAIN ), 'sort' ); ?>
			
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

new CPAC_Addon_Sorting;
