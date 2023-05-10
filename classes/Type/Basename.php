<?php
declare( strict_types=1 );

namespace AC\Type;

class Basename {

	private $basename;

	public function __construct( string $basename ) {
		$this->basename = $basename;
	}

	public function __toString(): string {
		return $this->basename;
	}

}