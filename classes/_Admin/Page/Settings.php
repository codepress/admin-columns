<?php

namespace AC\_Admin\Page;

use AC\_Admin\Page;

class Settings extends Page {

	const SLUG = 'settings';

	public function __construct() {
		parent::__construct( self::SLUG, __( 'Settings', 'codepress-admin-columns' ) );
	}

	public function render() {
		return '<h2>Settings</h2>';

		//		$view = new View();
		//		return $view->render();
	}

}