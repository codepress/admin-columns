<?php

class AC_Settings_Setting_PathScope extends AC_Settings_Setting
implements AC_Settings_FormatInterface {

	/**
	 * @var string
	 */
	private $path_scope = 'full';

	protected function set_managed_options() {
		$this->managed_options = array( 'path_scope' );
	}

	public function create_view() {
		$select = $this->create_element( 'select', 'path_scope' )
		              ->set_options( array(
			              'full'             => __( 'Full path', 'codepress-admin-columns' ),
			              'relative-domain'  => __( 'Relative to domain', 'codepress-admin-columns' ),
			              'relative-uploads' => __( 'Relative to main uploads folder ', 'codepress-admin-columns' ),
		              ) );

		$view = new AC_View( array(
			'label'   => __( 'Path scope', 'codepress-admin-columns' ),
			'tooltip' => __( 'Part of the file path to display', 'codepress-admin-columns' ),
			'setting' => $select,
		) );

		return $view;
	}

	/**
	 * @return string
	 */
	public function get_path_scope() {
		return $this->path_scope;
	}

	/**
	 * @param string $path_scope
	 *
	 * @return $this
	 */
	public function set_path_scope( $path_scope ) {
		$this->path_scope = $path_scope;

		return $this;
	}

	public function format( $file ) {
		$value = '';

		if ( $file ) {
			switch ( $this->get_path_scope() ) {
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

}