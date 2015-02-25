<?php
/**
 * If you like to register a column of your own please have a look at our documentation.
 * We also have a free start-kit available, which contains all the necessary files.
 *
 * Documentation: https://www.admincolumns.com/documentation/developer-docs/creating-new-column-type/
 * Starter-kit: https://github.com/codepress/cac-column-template/
 *
 */

/**
 * Manually set the columns for a storage model
 * This overrides the database settings and thus renders the settings screen for this storage model useless
 *
 * @since 2.2
 *
 * @param string $storage_model Storage model key
 * @param array $columns List of columns ([column_name] => [column_options])
 */
function ac_register_columns( $storage_model, $columns ) {
	global $_cac_exported_columns;

	$storage_models = (array) $storage_model;

	foreach ( $storage_models as $storage_model ) {
		if ( isset( $_cac_exported_columns[ $storage_model ] ) ) {
			$_cac_exported_columns[ $storage_model ] = array_merge( $_cac_exported_columns[ $storage_model ], $columns );
		}
		else {
			$_cac_exported_columns[ $storage_model ] = $columns;
		}
	}
}

function cpac_set_storage_model_columns( $storage_model, $columns ) {
	ac_register_columns( $storage_model, $columns );
}