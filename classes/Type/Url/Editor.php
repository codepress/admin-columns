<?php

namespace AC\Type\Url;

use AC\Admin;
use AC\Admin\RequestHandlerInterface;
use AC\Type;

class Editor implements Type\Url {

	/**
	 * @var string|null
	 */
	private $slug;

	public function __construct( $slug = null ) {
		$this->slug = $slug;
	}

	public function get_url() {
		$args = [
			RequestHandlerInterface::PARAM_PAGE => Admin\Admin::NAME,
		];

		if ( $this->slug ) {
			$args[ RequestHandlerInterface::PARAM_TAB ] = $this->slug;
		}

		return add_query_arg( $args, admin_url( 'options-general.php' ) );
	}

}