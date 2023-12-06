<?php

namespace AC\Settings\Column;

use AC;
use AC\Column;
use AC\Setting\ArrayImmutable;
use AC\Setting\Type\Value;
use AC\Settings;
use ACP\Expression\Specification;

class Images extends Settings\Column\Image implements AC\Setting\Recursive
{

    public function __construct(Column $column, Specification $specification = null)
    {
        parent::__construct($column, $specification);

        $this->name = 'images';
    }

    public function format(Value $value, ArrayImmutable $options): Value
    {
        //$images = ac_helper()->html->images(parent::format($collection->all(), $original_value), $removed);
        // TODO David: implement value collection
        return $value->with_value($value->get_value()[0] ?? null);
    }

    public function is_parent(): bool
    {
        return false;
    }

    public function get_children(): AC\Setting\SettingCollection
    {
        return new AC\Setting\SettingCollection([
            new NumberOfItems($this->column),
        ]);
    }
    
    //
    //	public function format( $value, $original_value ) {
    //		$collection = new Collection( (array) $value );
    //		$removed = $collection->limit( $this->column->get_setting( 'number_of_items' )->get_value() );
    //
    //		return ac_helper()->html->images( parent::format( $collection->all(), $original_value ), $removed );
    //	}

}