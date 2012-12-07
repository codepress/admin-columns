<?php

/**
 * CPAC_Export_Import Class
 *
 * @since     1.4.6.5
 *
 */
class CPAC_Export_Import
{		
	/**
	 * Constructor
	 *
	 * @since 1.4.6.5
	 */
	function __construct()
	{	
		add_action( 'wp_ajax_cpac_get_export', array( $this, 'get_export' ) );
		add_action( 'wp_ajax_cpac_import', array( $this, 'run_import' ) );
	}
	
	/**
	 * Get Export
	 *
	 * @since 1.4.6.5
	 */
	function get_export()
	{	
		if ( empty( $_POST['types'] ) )
			exit;
	
		$columns = array();
		foreach ( $_POST['types'] as $type ) {
			$columns[$type] = cpac_static::get_stored_columns( $type );			
		}	
		
		// make sure the array is not empty
		$columns = array_filter( $columns );
		
		if ( empty( $columns ) ) {
			echo json_encode( array( 'status' => 0, 'msg' => __('No settings founds.',  CPAC_TEXTDOMAIN ) ) );
			exit;
		}
		
		echo json_encode( array( 'status' => 1, 'msg' => "<!-- START: Admin Columns export -->\n" . base64_encode( serialize( $columns ) ) . "\n<!-- END: Admin Columns export -->" ) );		
		exit;
	}
	
	/**
	 * Run Import
	 *
	 * @since 1.4.6.5
	 */
	function run_import()
	{
		// @todo: add wp_nonce_verify (ajax)
		
		if ( empty( $_POST['import_code'] ) ) {
			echo json_encode( array( 'status' => 0, 'msg' => __('No import code found',  CPAC_TEXTDOMAIN ) ) );
			exit;
		}		
		
		// get code
		$import_code = $_POST['import_code'];
		
		// decode
		$import_code 	= str_replace( "<!-- START: Admin Columns export -->\n", "", $import_code );
		$import_code 	= str_replace( "\n<!-- END: Admin Columns export -->", "", $import_code );
		$import_code 	= base64_decode( trim( $import_code ) );
		$import_code  	= maybe_unserialize( $import_code );
		
		// validate code
		if ( empty( $import_code ) || ! is_array( $import_code ) ) {
			echo json_encode( array( 'status' => 0, 'msg' => __('Invalid import code',  CPAC_TEXTDOMAIN ) ) );
			exit;
		}
		
		// get current options
		$options = (array) get_option( 'cpac_options' );
		
		// merge saved setting if they exist..
		if ( !empty( $options['columns'] ) ) {
			$options['columns'] = array_merge( $options['columns'], $import_code );	
		}
		
		// .. if there are no setting yet use the import
		else {
			$options['columns'] = $import_code;
		}
		
		// save to DB				
		$result = update_option( 'cpac_options', $options );
		
		if ( $result ) {
			
			echo json_encode( array( 'status' => 1, 'msg' => __( sprintf( 'Imported succesfully. You have imported the following types: %s', '<strong>' . implode( ', ', array_keys( $import_code ) ) . '</strong>' ) ,  CPAC_TEXTDOMAIN ) ) );			
		}
		
		else {
			echo json_encode( array( 'status' => 0, 'msg' => __('Import aborted. Are you trying to store the same settings?',  CPAC_TEXTDOMAIN ) ) );			
		}
		exit;
	}
}

new CPAC_Export_Import;

?>