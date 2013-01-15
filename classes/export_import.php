<?php

/**
 * CPAC_Export_Import Class
 *
 * @since 1.4.6.5
 *
 */
class CPAC_Export_Import {

	/**
	 * Constructor
	 *
	 * @since 1.4.6.5
	 */
	function __construct() {
		add_action( 'wp_ajax_cpac_get_export', array( $this, 'get_export' ) );
		add_action( 'wp_ajax_cpac_import', array( $this, 'run_import' ) );
		
		add_action( 'admin_init', array( $this, 'download_export' ) );
		add_action( 'admin_init', array( $this, 'handle_file_import' ) );
	}

	/**
	 * Get Export
	 *
	 * @since 1.4.6.5
	 *
	 * @return string Export (JSON encode).
	 */
	function get_export() {
		if ( empty( $_POST['types'] ) ) {
			echo json_encode( array( 'status' => 0, 'msg' => __( 'No types selected.',  CPAC_TEXTDOMAIN ) ) );
			exit;
		}
		
		// get export string
		$export_string = $this->get_export_string( $_POST['types'] );
			
		if ( ! $export_string ) {
			echo json_encode( array( 'status' => 0, 'msg' => __( 'No settings founds. Did you save the columns settings for these types?',  CPAC_TEXTDOMAIN ) ) );
			exit;
		}

		echo json_encode( array( 
			'status' 		=> 1, 
			'msg' 			=> $export_string,
			'download_uri'	=> add_query_arg( array( 'page' => 'cpac-settings', '_cpac_nonce' => wp_create_nonce( 'download-export' ), 'export_types' => $_POST['types'] ), admin_url('admin.php') )
		));
		exit;
	}
	
	/**
	 * Get export string
	 *
	 * @since 2.0	 
	 */
	function get_export_string( $types = array() ) {
		
		if ( empty( $types ) )
			return false;
		
		$columns = array();
		foreach ( $types as $type ) {
			$columns[$type] = CPAC_Utility::get_stored_columns( $type );
		}
		
		$columns = array_filter( $columns );
		
		if ( empty( $columns ) )
			return false;
		
		return "<!-- START: Admin Columns export -->\n" . base64_encode( serialize( $columns ) ) . "\n<!-- END: Admin Columns export -->";		
	}		
	
	/**
	 * Download Export
	 *
	 * @since 2.0	 
	 */
	function download_export() {
		if ( ! isset( $_REQUEST['export_types'] ) || ! isset( $_REQUEST['_cpac_nonce'] ) || ! wp_verify_nonce( $_REQUEST['_cpac_nonce'], 'download-export' ) )
			return false;
		
		$single_type = '';
		if ( 1 == count( $_REQUEST['export_types'] ) ) {
			$single_type = '_' . $_REQUEST['export_types'][0];
		}
		
		$filename = 'admin-columns-export_' . date('Y-m-d', time() ) . $single_type;
		
		// generate text file
		header( "Content-disposition: attachment; filename={$filename}.txt" );
		header( 'Content-type: text/plain' );
		echo $this->get_export_string( $_REQUEST['export_types'] );
		exit;
	
	}
	
	/**
	 * Handle file import
	 *
	 * @uses wp_import_handle_upload() 
	 * @since 2.0	 
	 */
	function handle_file_import() {			
		if ( ! isset( $_REQUEST['_cpac_nonce'] ) || ! wp_verify_nonce( $_REQUEST['_cpac_nonce'], 'file-import' ) || empty( $_FILES['import'] ) )
			return false;
		
		// handles upload	
		$file = wp_import_handle_upload();
		
		// any errors?
		$error = '';
		if ( isset( $file['error'] ) ) {
			$error = '<p><strong>' . __( 'Sorry, there has been an error.', CPAC_TEXTDOMAIN ) . '</strong><br />' . esc_html( $file['error'] ) . '</p>';
		} else if ( ! file_exists( $file['file'] ) ) {
			$error = '<p><strong>' . __( 'Sorry, there has been an error.', CPAC_TEXTDOMAIN ) . '</strong><br />' . sprintf( __( 'The export file could not be found at <code>%s</code>. It is likely that this was caused by a permissions problem.', CPAC_TEXTDOMAIN ), esc_html( $file['file'] ) ) . '</p>';
		} else {
			$file_id = (int) $file['id'];
		}
		
		// @todo read file contents and start the import
		print_r($result);
		exit;
	}

	/**
	 * Run Import
	 *
	 * @since 1.4.6.5
	 *
	 * @return string Message ( JSON encoded ).
	 */
	function run_import() {
		// @todo: add wp_nonce_verify (ajax)

		if ( empty( $_POST['import_code'] ) ) {
			echo json_encode( array( 'status' => 0, 'msg' => __( 'No import code found',  CPAC_TEXTDOMAIN ) ) );
			exit;
		}

		// get code
		$import_code = $_POST['import_code'];

		// decode
		$import_code = str_replace( "<!-- START: Admin Columns export -->\n", "", $import_code );
		$import_code = str_replace( "\n<!-- END: Admin Columns export -->", "", $import_code );
		$import_code = maybe_unserialize( base64_decode( trim( $import_code ) ) );

		// validate code
		if ( empty( $import_code ) || ! is_array( $import_code ) ) {
			echo json_encode( array( 'status' => 0, 'msg' => __( 'Invalid import code',  CPAC_TEXTDOMAIN ) ) );
			exit;
		}

		// get current options
		$options = (array) get_option( 'cpac_options' );

		// merge saved setting if they exist..
		if ( ! empty( $options['columns'] ) ) {
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
			echo json_encode( array( 'status' => 0, 'msg' => __( 'Import aborted. Are you trying to store the same settings?',  CPAC_TEXTDOMAIN ) ) );
		}
		exit;
	}
}

new CPAC_Export_Import;