<?php
defined( 'ABSPATH' ) or die();

/**
 * @since 2.0
 */
class AC_Column_Media_FullPath extends AC_Column {

	public function __construct() {
		$this->set_type( 'column-full_path' );
		$this->set_label( __( 'Full path', 'codepress-admin-columns' ) );
	}

	private function get_pathscope() {
		return $this->get_option( 'path_scope' );
	}

	// Display
	public function get_value( $id ) {
		$value = '';

		if ( $file = wp_get_attachment_url( $id ) ) {
			switch ( $this->get_pathscope() ) {
				case 'relative-domain' :
					$file = str_replace( 'https://', 'http://', $file );
					$url = str_replace( 'https://', 'http://', home_url( '/' ) );

					if ( strpos( $file, $url ) === 0 ) {
						$file = '/' . substr( $file, strlen( $url ) );
					}

					break;
				case 'relative-uploads' :
					$uploaddir = wp_upload_dir();
					$file = str_replace( 'https://', 'http://', $file );
					$url = str_replace( 'https://', 'http://', $uploaddir['baseurl'] );

					if ( strpos( $file, $url ) === 0 ) {
						$file = substr( $file, strlen( $url ) );
					}

					break;
			}

			$value = $file;
		}

		return $value;
	}

	// Settings
	public function display_settings() {
		$this->field_settings->field( array(
			'type'        => 'radio',
			'name'        => 'path_scope',
			'label'       => __( 'Path scope', 'codepress-admin-columns' ),
			'description' => __( 'Part of the file path to display', 'codepress-admin-columns' ),
			'vertical'    => true,
			'options'     => array(
				'full'             => __( 'Full path', 'codepress-admin-columns' ),
				'relative-domain'  => __( 'Relative to domain', 'codepress-admin-columns' ),
				'relative-uploads' => __( 'Relative to main uploads folder ', 'codepress-admin-columns' ),
			),
			'default_value'     => 'full'
		) );
	}

}