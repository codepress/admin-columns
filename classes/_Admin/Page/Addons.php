<?php

namespace AC\_Admin\Page;

use AC\_Admin\Page;

class Addons extends Page {

	const SLUG = 'addons';

	public function __construct() {
		parent::__construct( self::SLUG, __( 'Add-ons', 'codepress-admin-columns' ) );
	}

	public function render() {
		return '<h2>Add-ons</h2>';

		//		$view = new View();
		//		return $view->render();
	}

}