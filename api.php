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

	global $cpac;

	if ( $storage_model = $cpac->get_storage_model( $storage_model ) ) {
		$storage_model->set_stored_columns( $columns );
	}
}
