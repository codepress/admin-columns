<?php

class AC_Settings_Column_Images extends AC_Settings_Column_Image {

	protected function set_name() {
		return $this->name = 'images';
	}

	public function get_dependent_settings() {
		return array( new AC_Settings_Column_NumberOfItems( $this->column ) );
	}

	public function format( $value, $original_value ) {
		$collection = new AC_Collection( (array) $value );
		$removed = $collection->limit( $this->column->get_setting( 'number_of_items' )->get_value() );

		return ac_helper()->html->images( parent::format( $collection->all(), $original_value ), $removed );
	}

}