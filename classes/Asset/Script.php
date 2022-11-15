<?php

namespace AC\Asset;

use AC\Asset\Script\Inline\Data\Variable;
use AC\Asset\Script\Inline\Position;
use AC\Stringable;
use AC\Translation\Translation;
use InvalidArgumentException;

class Script extends Enqueueable {

	protected function is_registered(): bool {
		return wp_script_is( $this->get_handle(), 'registered' );
	}

	public function register(): void {
		if ( ! $this->location instanceof Location ) {
			return;
		}

		wp_register_script(
			$this->get_handle(),
			$this->location->get_url(),
			$this->dependencies,
			$this->get_version()
		);
	}

	public function enqueue(): void {
		if ( wp_script_is( $this->get_handle() ) ) {
			return;
		}

		if ( $this->is_registered() ) {
			$this->register();
		}

		wp_enqueue_script( $this->get_handle() );
	}

	public function localize( string $name, Translation $translation ): self {
		if ( ! $this->is_registered() ) {
			$this->register();
		}

		wp_localize_script( $this->handle, $name, $translation->get_translation() );

		return $this;
	}

	public function add_inline( $data, Position $position = null ): self {
		if ( ! is_string( $data ) && ! $data instanceof Stringable ) {
			throw new InvalidArgumentException( 'Expected string or an object that implements Stringable.' );
		}

		if ( ! $this->is_registered() ) {
			$this->register();
		}

		if ( null === $position ) {
			$position = Position::after();
		}

		wp_add_inline_script( $this->handle, (string) $data, (string) $position );

		return $this;
	}

	// TODO Stefan remove once ported
	public function add_inline_variable( $name, $data ) {
		$this->add_inline( (string) new Variable( $name, $data ), Position::before() );
	}

}
