<?php
/**
 * Manually set the columns for a storage model
 * This overrides the database settings and thus renders the settings screen for this storage model useless
 *
 * @since 2.2
 *
 * @param string $storage_model Storage model key
 * @param array $columns List of columns ([column_name] => [column_options])
 */
function cpac_set_storage_model_columns( $storage_model, $columns ) {
	global $_cac_exported_columns;
	$_cac_exported_columns = array( $storage_model => $columns );

	add_action( 'cac/loaded', 'cpac_set_exported_columns', 5 );
}

/**
 * Set exported columns
 *
 * @since 3.2
 */
function cpac_set_exported_columns( $cpac ) {
	global $_cac_exported_columns;

	$model = key( $_cac_exported_columns );
	$columns = $_cac_exported_columns[ $model ];

	if ( $storage_model = $cpac->get_storage_model( $model ) ) {
		$storage_model->set_stored_columns( $columns );
	}
}
