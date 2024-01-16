<?php

namespace AC\Settings\Column;

use AC;
use AC\Settings;
use AC\Expression\Specification;

class VideoDisplay extends Settings\Column
{

    public function __construct(AC\Column $column, Specification $conditions = null)
    {
        $this->name = 'video_display';
        $this->label = __('Display', 'codepress-admin-columns');
        $this->input = AC\Setting\Input\Option\Single::create_select(
            AC\Setting\OptionCollection::from_array([
                'embed' => __('Embed', 'codepress-admin-columns'),
                'modal' => __('Pop Up', 'codepress-admin-columns'),
            ]),
            'embed'
        );

        parent::__construct(
            $column,
            $conditions
        );
    }

}