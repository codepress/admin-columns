<?php

namespace AC\Message;

use AC\Asset\Style;
use AC\Message;
use AC\Registrable;
use AC\View;

class Notice extends Message implements Registrable {

	public function render() {
		$data = [
			'message' => $this->message,
			'type'    => $this->type,
			'id'      => $this->id,
		];

		$view = new View( $data );
		$view->set_template( 'message/notice' );

		return $view->render();
	}

	public function register() {
		if ( apply_filters( 'ac/suppress_site_wide_notices', false ) ) {
			return;
		}

		add_action( 'admin_notices', [ $this, 'display' ] );
		add_action( 'network_admin_notices', [ $this, 'display' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}

	public function enqueue_scripts() {
		$style = new Style( 'ac-message', AC()->get_location()->with_suffix( 'assets/css/notice.css' ) );
		$style->enqueue();
	}

}