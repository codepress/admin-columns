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
		add_action( 'admin_init', array( $this, 'download_export' ) );
		add_action( 'admin_init', array( $this, 'handle_file_import' ) );
	}

	/**
	 * Get export string
	 *
	 * @since 2.0.0
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
	 * @since 2.0.0
	 */
	function download_export() {
		if ( ! isset( $_REQUEST['_cpac_nonce'] ) || ! wp_verify_nonce( $_REQUEST['_cpac_nonce'], 'download-export' ) )
			return false;

		if ( empty( $_REQUEST['export_types'] ) ) {
			CPAC_Utility::admin_message( "<p>" . __( 'Export field is empty. Please select your types from the left column.',  CPAC_TEXTDOMAIN ) . "</p>", 'error' );
			return false;
		}

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
	 * @since 2.0.0
	 */
	function handle_file_import() {
		if ( ! isset( $_REQUEST['_cpac_nonce'] ) || ! wp_verify_nonce( $_REQUEST['_cpac_nonce'], 'file-import' ) || empty( $_FILES['import'] ) )
			return false;

		// handles upload
		$file = wp_import_handle_upload();

		// any errors?
		$error = false;
		if ( isset( $file['error'] ) ) {
			$error = '<p><strong>' . __( 'Sorry, there has been an error.', CPAC_TEXTDOMAIN ) . '</strong><br />' . esc_html( $file['error'] ) . '</p>';
		} else if ( ! file_exists( $file['file'] ) ) {
			$error = '<p><strong>' . __( 'Sorry, there has been an error.', CPAC_TEXTDOMAIN ) . '</strong><br />' . sprintf( __( 'The export file could not be found at <code>%s</code>. It is likely that this was caused by a permissions problem.', CPAC_TEXTDOMAIN ), esc_html( $file['file'] ) ) . '</p>';
		}

		if ( $error ) {
			CPAC_Utility::admin_message( $error, 'error' );
			return false;
		}
		// read file contents and start the import
		$content = file_get_contents( $file['file'] );

		// decode file contents
		if ( ! $columns = $this->get_decoded_settings( $content ) ) {
			CPAC_Utility::admin_message( "<p>" . __( 'Import failed. File does not contain Admin Column settings.',  CPAC_TEXTDOMAIN ) . "</p>", 'error' );
			return false;
		}

		// store settings
		if ( ! $result = $this->update_settings( $columns ) ) {
			CPAC_Utility::admin_message( "<p>" . __( 'Import aborted. Are you trying to store the same settings?',  CPAC_TEXTDOMAIN ) . "</p>", 'error' );
			return false;
		}

		CPAC_Utility::admin_message( "<p>" . __( sprintf( 'Import succesfully. You have imported the following types: %s', '<strong>' . implode( ', ', array_keys( $columns ) ) . '</strong>' ) ,  CPAC_TEXTDOMAIN ) . "</p>", 'updated' );
	}

	/**
	 * Get decoded settings
	 *
	 * @since 2.0.0
	 *
	 * @param string $encoded_string
	 * @return array Columns
	 */
	function get_decoded_settings( $encoded_string = '' ) {
		if( ! $encoded_string || ! is_string( $encoded_string ) || strpos( $encoded_string, '<!-- START: Admin Columns export -->' ) === false )
			return false;

		// decode
		$encoded_string = str_replace( "<!-- START: Admin Columns export -->\n", "", $encoded_string );
		$encoded_string = str_replace( "\n<!-- END: Admin Columns export -->", "", $encoded_string);
		$decoded 	 	= maybe_unserialize( base64_decode( trim( $encoded_string ) ) );

		if ( empty( $decoded ) || ! is_array( $decoded ) )
			return false;

		return $decoded;
	}

	/**
	 * Update settings
	 *
	 * @since 2.0.0
	 *
	 * @param array $columns Columns
	 * @return bool
	 */
	function update_settings( $columns ) {
		$options = get_option( 'cpac_options' );

		// merge saved setting if they exist..
		if ( ! empty( $options['columns'] ) ) {
			$options['columns'] = array_merge( $options['columns'], $columns );
		}

		// .. if there are no setting yet use the import
		else {
			$options = array(
				'columns' => $columns
			);
		}

		return update_option( 'cpac_options', array_filter( $options ) );
	}
}

new CPAC_Export_Import;