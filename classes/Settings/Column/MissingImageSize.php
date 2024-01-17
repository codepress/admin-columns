<?php

namespace AC\Settings\Column;

use AC;
use AC\Expression\Specification;
use AC\Setting\OptionCollection;
use AC\Settings;

class MissingImageSize extends Settings\Column
{

    public function __construct(Specification $conditions = null)
    {
        $input = AC\Setting\Input\Option\Single::create_toggle(
            OptionCollection::from_array([
                '1',
                '',
            ], false),
            ''
        );

        parent::__construct(
            'include_missing_sizes',
            __('Include missing sizes?', 'codepress-admin-columns'),
            __('Include sizes that are missing an image file.', 'codepress-admin-columns'),
            $input,
            $conditions
        );
    }

}