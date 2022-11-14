<?php

namespace AC\Asset;

use InvalidArgumentException;

class InlineScript {

	public const BEFORE = 'before';
	public const AFTER = 'after';

	protected $handle;

	protected $data;

	protected $position;

	public function __construct( string $handle, string $data, $position = null ) {
		if ( null === $position ) {
			$position = self::AFTER;
		}

		$this->handle = $handle;
		$this->data = $data;
		$this->position = $position;

		$this->validate();
	}

	public function create_variable(
		string $handle,
		string $variable_name,
		$data,
		string $position = null
	): InlineScript {
		$type = gettype( $data );

		switch ( $type ) {
			case 'array':
				$data = json_encode( $data );

				break;
			case 'boolean':
				$data = $data ? 'true' : 'false';

				break;
		}

		$data = sprintf( "var %s = %s;", $variable_name, $data );

		return new self( $handle, $data, $position );
	}

	public function add(): void {
		wp_add_inline_script(
			$this->handle,
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