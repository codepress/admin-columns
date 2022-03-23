<?php

namespace AC\Type\Url;

use AC\Admin;
use AC\Admin\RequestHandlerInterface;
use AC\Type;

class EditorNetwork implements Type\QueryAware {

	use Type\QueryAwareTrait;

	public function __construct( $slug = null ) {
		$this->url = network_admin_url( 'settings.php' );

		$this->add_one( RequestHandlerInterface::PARAM_PAGE, Admin\Admin::NAME );

		if ( $slug ) {
			$this->add_one( RequestHandlerInterface::PARAM_TAB, $slug );
		}
	}

}