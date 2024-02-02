<?php

namespace AC\Settings\Column;

use AC;
use AC\Expression\Specification;
use AC\Settings;

// TODO implement formatter (Interface was CollectionFormatter)
class Separator extends Settings\Column
{

    public const NAME = 'separator';

    public function __construct(string $separator = null, Specification $conditions = null)
    {
        $input = AC\Setting\Component\Input\OptionFactory::create_select(
            'separator',
            AC\Setting\Component\OptionCollection::from_array([
                ''                => __('Default', 'codepress-admin-columns'),
                'comma'           => __('Comma Separated', 'codepress-admin-columns'),
                'horizontal_rule' => __('Horizontal Rule', 'codepress-admin-columns'),
                'newline'         => __('New Line', 'codepress-admin-columns'),
                'none'            => __('None', 'codepress-admin-columns'),
                'white_space'     => __('Whitespace', 'codepress-admin-columns'),
            ]),
            $separator ?: 'comma'
        );

        parent::__construct(
            __('Separator', 'codepress-admin-columns'),
            '',
            $input,
            $conditions
        );
    }

    // TODO formatter
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