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
	global $_acp_export_columns;
	$_acp_export_columns = $columns;
	add_filter( 'cpac/storage_model/stored_columns/storage_key=' . $storage_model, 'cpac_set_exported_columns' );
}

/**
 * Set exported columns
 *
 * @since 3.2
 */
function cpac_set_exported_columns( $columns ) {
	global $_acp_export_columns;
	if ( $_acp_export_columns ) {
		$columns = $_acp_export_columns;
	}
	return $columns;
}
