<?php

namespace AC\Settings\Column;

use AC;
use AC\Expression\Specification;
use AC\Setting\OptionCollection;
use AC\Settings;

class VideoDisplay extends Settings\Column
{

    public function __construct(Specification $conditions = null)
    {
        $input = AC\Setting\Input\Option\Single::create_select(
            OptionCollection::from_array([
                'embed' => __('Embed', 'codepress-admin-columns'),
                'modal' => __('Pop Up', 'codepress-admin-columns'),
            ]),
            'embed'
        );

        parent::__construct(
            'video_display',
            __('Display', 'codepress-admin-columns'),
            null,
            $input,
            $conditions
        );
    }

}