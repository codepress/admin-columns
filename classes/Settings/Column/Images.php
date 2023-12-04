<?php

namespace AC\Settings\Column;

use AC\Column;
use AC\Settings;
use ACP\Expression\Specification;

class Images extends Settings\Column\Image
{

    public function __construct(Column $column, Specification $specification = null)
    {
        parent::__construct($column, $specification);

        $this->name = 'images';
    }

    //	protected function set_name() {
    //		return $this->name = 'images';
    //	}
    //
    //	public function get_dependent_settings() {
    //		return [ new Settings\Column\NumberOfItems( $this->column ) ];
    //	}
    //
    //	public function format( $value, $original_value ) {
    //		$collection = new Collection( (array) $value );
    //		$removed = $collection->limit( $this->column->get_setting( 'number_of_items' )->get_value() );
    //
    //		return ac_helper()->html->images( parent::format( $collection->all(), $original_value ), $removed );
    //	}

}