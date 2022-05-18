<?php declare( strict_types=1 );

namespace AC\Admin\Colors;

use AC\Iterator;
use AC\Admin\Colors\Type\Color;

final class ColorCollection extends Iterator {

	public function __construct( array $colors = [] ) {
		array_map( [ $this, 'add' ], $colors );
	}

	public function add( Color $color ): void {
		$this->data[] = $color;
	}

	public function current(): Color {
		return parent::current();
	}

}