<?php

namespace AC\Settings\Column;

use AC;
use AC\Settings;
use ACP\Expression\Specification;

// TODO implement formatter (Interface was CollectionFormatter)
class Separator extends Settings\Column
{

    public const NAME = 'separator';

    public function __construct(AC\Column $column, Specification $conditions = null)
    {
        $this->name = self::NAME;
        $this->label = __('Separator', 'codepress-admin-columns');
        $this->input = AC\Setting\Input\Option\Single::create_select(
            AC\Setting\OptionCollection::from_array([
                ''                => __('Default', 'codepress-admin-columns'),
                'comma'           => __('Comma Separated', 'codepress-admin-columns'),
                'horizontal_rule' => __('Horizontal Rule', 'codepress-admin-columns'),
                'newline'         => __('New Line', 'codepress-admin-columns'),
                'none'            => __('None', 'codepress-admin-columns'),
                'white_space'     => __('Whitespace', 'codepress-admin-columns'),
            ]),
            'comma'
        );

        parent::__construct(
            $column,
            $conditions
        );
    }

    //
    //    public function get_separator_formatted()
    //    {

    //    }
    //
    //    public function format(Collection $collection, $original_value)
    //    {
    //        return $collection->filter()->implode($this->get_separator_formatted());
    //    }

}