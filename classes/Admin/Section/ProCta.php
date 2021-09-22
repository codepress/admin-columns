<?php

namespace AC\Admin\Section;

use AC\Admin\Section;
use AC\View;

class ProCta extends Section {

	const NAME = 'pro-cta';

	public function __construct() {
		parent::__construct( self::NAME );
	}

	public function render() {
		$view = new View( [
			'title'       => 'Admin Columns Pro',
			'description' => __( 'Upgrade to Admin Columns Pro and unlock all the awesome features.', 'codepress-admin-columns' ),
		] );

		$view->set_template( 'admin/page/settings-section-pro-cta' );

		return $view->render();
	}

}