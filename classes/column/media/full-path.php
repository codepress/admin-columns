<?php
defined( 'ABSPATH' ) or die();

/**
 * CPAC_Column_Media_Full_Path
 *
 * @since 2.0
 */
class CPAC_Column_Media_Full_Path extends CPAC_Column {

	public function init() {
		parent::init();

		$this->properties['type'] = 'column-full_path';
		$this->properties['label'] = __( 'Full path', 'codepress-admin-columns' );

		$this->options['path_scope'] = 'full';
	}

	private function get_pathscope() {
		return $this->get_option( 'path_scope' );
	}

	// Display
	function get_value( $id ) {
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
		$this->form_field( array(
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
			'default'     => 'full'
		) );
	}
}