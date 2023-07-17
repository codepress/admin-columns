<?php
declare( strict_types=1 );

namespace AC\Type\Url;

use AC\Type\Url;

class WordpressPluginReview implements Url {

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

	public function get_url(): string
    {
		return sprintf( 'https://wordpress.org/support/plugin/%s/reviews/#postform', $this->handle );
	}

}