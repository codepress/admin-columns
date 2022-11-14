<?php

namespace AC\Asset;

use AC\Translation\Translation;

class Script extends Enqueueable {

	public function register() {
		if ( null === $this->location ) {
			return;
		}

		wp_register_script(
			$this->get_handle(),
			$this->location->get_url(),
			$this->dependencies,
			$this->get_version()
		);
	}

	public function localize( string $name, Translation $translation ): self {
		wp_localize_script( $this->handle, $name, $translation->get_translation() );

		return $this;
	}

	public function add_inline_script( InlineScript\Data $data, InlineScript\Position $position ): self {
		wp_add_inline_script( $this->handle, $data, (string) $position );

		return $this;
	}

	public function enqueue() {
		if ( wp_script_is( $this->get_handle() ) ) {
			return;
		}

		if ( ! wp_script_is( $this->get_handle(), 'registered' ) ) {
			$this->register();
		}

		wp_enqueue_script( $this->get_handle() );
	}

}
