<?php

/**
 * CPAC_Column_ACF_Placeholder
 *
 * @since 1.0
 */
class CPAC_Column_ACF_Placeholder extends CPAC_Column {

	private $user_settings;

	function __construct( $storage_model ) {

		// define properties
		$this->properties['type']	 		= 'column-acf_placeholder';
		$this->properties['label']	 		= __( 'ACF Field', 'cpac' );
		$this->properties['is_pro_only']	= true;

		// call construct
		parent::__construct( $storage_model );
	}

	/**
	 * @see CPAC_Column::display_settings()
	 * @since 1.0
	 */
	function display_settings() {

		?>
		<div class="is-disabled">
			<p><strong><?php _e( 'This feature is only available in Admin Columns Premium.' ); ?></strong></p>
			<p><?php _e( 'Admin Columns Premium offers full Advanced Custom Fields integeration, allowing you to easily display and edit ACF fields from within your posts overview.', 'cpac' ); ?></p>
			<a href="http://admincolumns.com/advanced-custom-fields-integration/" class="button button-primary"><?php _e( 'Find out more', 'cpac' ); ?></a>
		</div>
		<?php
	}
}