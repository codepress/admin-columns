<?php

namespace AC\Admin\Section;

use AC\Admin\Section;
use AC\View;

class Restore extends Section {

	const NAME = 'restore';

	public function __construct() {
		parent::__construct( self::NAME );
	}

	public function render() {
		$form = ( new View() )->set_template( 'admin/page/settings-section-restore' );

		$view = new View( [
			'title'       => __( 'Restore Settings', 'codepress-admin-columns' ),
			'description' => __( 'Delete all column settings and restore the default settings.', 'codepress-admin-columns' ),
			'content'     => $form->render(),
			'class'       => 'general',
		] );

		$view->set_template( 'admin/page/settings-section' );

		return $view->render();
	}

}