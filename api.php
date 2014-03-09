<?php
function cpac_set_storage_model_columns( $storage_model, $columns ) {

	global $cpac;

	if ( $storage_model = $cpac->get_storage_model( $storage_model ) ) {
		$storage_model->set_stored_columns( $columns );
	}
}
?>