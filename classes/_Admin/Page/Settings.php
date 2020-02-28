<?php

namespace AC\_Admin\Page;

use AC\_Admin\Page;
use AC\_Admin\Renderable;
use AC\View;

class Settings extends Page {

	const SLUG = 'settings';

	/**
	 * @var Renderable[]
	 */
	private $renderables;

	public function __construct( array $renderables ) {
		parent::__construct( self::SLUG, __( 'Settings', 'codepress-admin-columns' ) );

		$this->renderables = $renderables;
	}

	public function render() {
		$view = new View( [
			'sections' => $this->renderables,
		] );
		$view->set_template( 'admin/page/settings' );

		return $view->render();
	}

}