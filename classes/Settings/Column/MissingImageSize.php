<?php

namespace AC\Settings\Column;

use AC;
use AC\Settings;
use AC\Expression\Specification;

class MissingImageSize extends Settings\Column
{

    public function __construct(AC\Column $column, Specification $conditions = null)
    {
        $this->name = 'include_missing_sizes';
        $this->label = __('Include missing sizes?', 'codepress-admin-columns');
        $this->description = __('Include sizes that are missing an image file.', 'codepress-admin-columns');
        $this->input = AC\Setting\Input\Option\Single::create_toggle(
            AC\Setting\OptionCollection::from_array([
                '1',
                '',
            ], false),
            ''
        );

        parent::__construct(
            $column,
            $conditions
        );
    }

}