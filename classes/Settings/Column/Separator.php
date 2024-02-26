<?php

namespace AC\Settings\Column;

use AC;
use AC\Expression\Specification;
use AC\Settings;

class Separator extends Settings\Control
{

    public function __construct(string $separator, Specification $specification = null)
    {
        parent::__construct(
            AC\Setting\Component\Input\OptionFactory::create_select(
                'separator',
                AC\Setting\Component\OptionCollection::from_array([
                    ''                => __('Default', 'codepress-admin-columns'),
                    'comma'           => __('Comma Separated', 'codepress-admin-columns'),
                    'horizontal_rule' => __('Horizontal Rule', 'codepress-admin-columns'),
                    'newline'         => __('New Line', 'codepress-admin-columns'),
                    'none'            => __('None', 'codepress-admin-columns'),
                    'white_space'     => __('Whitespace', 'codepress-admin-columns'),
                ]),
                $separator
            ),
            __('Separator', 'codepress-admin-columns'),
            $specification
        );
    }

}