<?php

class CPAC_Addon_Filtering {

	function __construct() {

		// add js
		//add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

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
	 * Settings
	 *
	 * @since 2.0.0
	 */
	function settings( $column ) {		
	
		?>
		<tr class="column_filtering">
		
			<?php $column->label_view( __( 'Enable filtering?', CPAC_TEXTDOMAIN ), __( 'This will add a dropdown for filtering.', CPAC_TEXTDOMAIN ), 'filtering' ); ?>
			
			<td class="input">
				<label for="<?php $column->attr_id( 'filtering' ); ?>-on">
					<input type="radio" value="on" name="<?php $column->attr_name( 'filtering' ); ?>" id="<?php $column->attr_id( $field_key ); ?>-on"<?php checked( $column->options->filtering, 'on' ); ?>>
					<?php _e( 'Yes'); ?>
				</label>
				<label for="<?php $column->attr_id( 'filtering' ); ?>-off">
					<input type="radio" value="" name="<?php $column->attr_name( 'filtering' ); ?>" id="<?php $column->attr_id( 'filtering' ); ?>-off"<?php checked( $column->options->filtering, '' ); ?><?php checked( $column->options->filtering, 'off' ); ?>>
					<?php _e( 'No'); ?>
				</label>
			</td>
		</tr>
	<?php
	}
	
}

new CPAC_Addon_Filtering;
