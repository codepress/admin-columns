<?php

namespace AC\_Admin\Page\Settings;

use AC\_Admin\Renderable;
use AC\View;

class General implements Renderable {

	/**
	 * @var Renderable[]
	 */
	private $renderables;

	public function __construct( array $renderables ) {
		$this->renderables = $renderables;
	}

	public function render() {
		$form = new View( [
			'options' => $this->renderables,
		] );

		$form->set_template( 'admin/page/settings-section-general' );

		$view = new View( [
			'title'       => __( 'General Settings', 'codepress-admin-columns' ),
			'description' => __( 'Customize your Admin Columns settings.', 'codepress-admin-columns' ),
			'content'     => $form->render(),
			'class'       => 'general',
		] );

		$view->set_template( 'admin/page/settings-section' );

		return $view->render();
	}

}