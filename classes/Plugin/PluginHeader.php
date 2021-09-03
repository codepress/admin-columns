<?php

namespace AC\Plugin;

class PluginHeader {

	const AUTHOR = 'AuthorName';
	const DESCRIPTION = 'Description';
	const NAME = 'Name';
	const PLUGIN_URI = 'PluginURI';
	const REQUIRES_PHP = 'RequiresPHP';
	const REQUIRES_WP = 'RequiresWP';
	const TITLE = 'Title';
	const VERSION = 'Version';

	/**
	 * @var string
	 */
	private $file;

	public function __construct( $file ) {
		$this->file = (string) $file;
	}

	/**
	 * @param string $var
	 *
	 * @return string|null
	 */
	public function get( $var ) {
		$data = $this->get_data();

		return isset( $data[ $var ] )
			? (string) $data[ $var ]
			: null;
	}

	/**]
	 * @return Version
	 */
	public function get_version() {
		return new Version( (string) $this->get( self::VERSION ) );
	}

	/**
	 * @return array
	 */
	private function get_data() {
		static $data = null;

		require_once ABSPATH . 'wp-admin/includes/plugin.php';

		if ( null === $data ) {
			$data = get_plugin_data( $this->file, false, false );
		}

		return $data;
	}

}