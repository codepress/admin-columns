<?php

namespace AC\Admin\Page;

use AC\Admin\Page;

class Tools extends Page {

	const SLUG = 'tools';

	public function __construct() {
		parent::__construct( self::SLUG, __( 'Tools', 'codepress-admin-columns' ) );
	}

	public function render() {
		return '<h2>Tools</h2>';

		//		$view = new View();
		//		return $view->render();
	}

}