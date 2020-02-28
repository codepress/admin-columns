<?php

namespace AC\_Admin\Page\Settings;

use AC\_Admin\Renderable;
use AC\View;

class Restore implements Renderable {

	public function render() {
		$form = ( new View() )->set_template( 'admin/page/settings-section-restore' );

		$view = new View( [
			'title'       => __( 'Restore Settings', 'codepress-admin-columns' ),
			'description' => __( 'This will delete all column settings and restore the default settings.', 'codepress-admin-columns' ),
			'content'     => $form->render(),
			'class'       => 'general',
		] );

		$view->set_template( 'admin/page/settings-section' );

		return $view->render();
	}

}