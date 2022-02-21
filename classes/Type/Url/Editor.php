<?php

namespace AC\Type\Url;

use AC\Admin;
use AC\Admin\RequestHandlerInterface;
use AC\Type;

class Editor implements Type\QueryAware {

	use Type\QueryAwareTrait;

	public function __construct( $slug = null ) {
		$this->url = admin_url( 'options-general.php' );

		$this->add_one( RequestHandlerInterface::PARAM_PAGE, Admin\Admin::NAME );

		if ( $slug ) {
			$this->add_one( RequestHandlerInterface::PARAM_TAB, $slug );
		}
	}

}