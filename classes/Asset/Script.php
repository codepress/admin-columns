<?php

namespace AC\Asset;

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

	public function add_inline_variable( $name, $variable, $before = true ) {
		if ( is_array( $variable ) ) {
			$variable = json_encode( $variable );
		}

		if ( is_bool( $variable ) ) {
			$variable = $variable ? 'true' : 'false';
		}

		wp_add_inline_script( $this->get_handle(), sprintf(
			"var %s = %s;",
			(string) $name,
			$variable
		), $before ? 'before' : 'after' );
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
