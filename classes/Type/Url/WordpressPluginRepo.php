<?php
declare( strict_types=1 );

namespace AC\Type\Url;

use AC\Type\Url;

class WordpressPluginRepo implements Url {

	/**
	 * @var string
	 */
	private $handle;

	public function __construct() {
		$this->handle = 'codepress-admin-columns';
	}

	public function get_handle(): string {
		return $this->handle;
	}

	public function get_url() {
		return sprintf( 'https://wordpress.org/plugins/%s/', $this->handle );
	}

}