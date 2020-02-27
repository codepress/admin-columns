<?php

namespace AC\_Admin\Page;

use AC\_Admin\Page;

class Columns extends Page {

	const SLUG = 'columns';

	public function __construct() {
		parent::__construct( self::SLUG, __( 'Admin Columns', 'codepress-admin-columns' ) );
	}

	public function render() {
		return '<h2>Columns</h2>';

		//		$view = new View();
		//		return $view->render();
	}

}