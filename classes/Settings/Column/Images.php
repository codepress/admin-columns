<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC;
use AC\Expression\Specification;
use AC\Setting\ArrayImmutable;
use AC\Setting\Type\Value;
use AC\Settings;

class Images extends Settings\Column\Image implements AC\Setting\Recursive
{

    public function __construct(AC\Column $column, Specification $specification = null)
    {
        parent::__construct($column, $specification);

        $this->name = 'images';
    }

    public function format(Value $value, ArrayImmutable $options): Value
    {
        $values = [];
        $image_ids = $value->get_value();

        if ( ! is_array($image_ids)) {
            return $value;
        }

        $image_count = (int)$options->get('number_of_items');

        $ids_subset = array_slice($image_ids, 0, $image_count);
        $hidden_count = count($value->get_value()) - $image_count;

        foreach ($ids_subset as $id) {
            $values[] = parent::format($value->with_value($id), $options)->get_value();
        }

        return $value->with_value(
            ac_helper()->html->images(implode(' ', $values), $hidden_count)
        );
    }

    public function is_parent(): bool
    {
        return false;
    }

    public function get_children(): AC\Setting\SettingCollection
    {
        $settings = parent::get_children();
        $settings->add(new NumberOfItems($this->column));

        return $settings;
    }

}