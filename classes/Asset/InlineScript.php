<?php

namespace AC\Asset;

use InvalidArgumentException;

class InlineScript {

	const BEFORE = 'before';
	const AFTER = 'after';

	protected $position;

	protected Script $script;

	protected string $data;

	public function __construct( Script $script, string $data, $position = null ) {
		if ( null === $position ) {
			$position = self::AFTER;
		}

		$this->script = $script;
		$this->data = $data;
		$this->position = $position;

		$this->validate();
	}

	public function add(): void {
		wp_add_inline_script(
			$this->script->get_handle(),
			$this->data,
			$this->position
		);
	}

	private function validate(): void {
		$valid_positions = [
			self::BEFORE,
			self::AFTER,
		];

		if ( ! in_array( $this->position, $valid_positions, true ) ) {
			throw new InvalidArgumentException( 'Invalid script position provided' );
		}
	}

}