<?php

namespace AC\Asset;

class Style extends Enqueueable {

	public function register() {
		if ( null === $this->location ) {
			return;
		}

		wp_register_style(
			$this->get_handle(),
			$this->location->get_url(),
			$this->dependencies,
			$this->get_version()
		);
	}

	public function enqueue() {
		if ( wp_style_is( $this->get_handle() ) ) {
			return;
		}

		if ( ! wp_style_is( $this->get_handle(), 'registered' ) ) {
			$this->register();
		}

		wp_enqueue_style( $this->get_handle() );
	}

}