<?php

namespace AC\Settings\Column;

use AC\Collection;
use AC\Settings;

class Images extends Settings\Column\Image {

	protected function set_name() {
		return $this->name = 'images';
	}

	public function get_dependent_settings() {
		return [ new Settings\Column\NumberOfItems( $this->column ) ];
	}

	public function format( $value, $original_value ) {
		$collection = new Collection( (array) $value );
		$removed = $collection->limit( $this->column->get_setting( 'number_of_items' )->get_value() );

		return ac_helper()->html->images( parent::format( $collection->all(), $original_value ), $removed );
	}

}