<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC;
use AC\Setting\Component\Input\OptionFactory;
use AC\Setting\Component\OptionCollection;
use AC\Settings;
use ACP\Expression\Specification;

class VideoDisplay extends Settings\Column
{

    public function __construct(AC\Column $column, Specification $conditions = null)
    {
        $this->name = 'video_display';
        $this->label = __('Display', 'codepress-admin-columns');
        $this->input = OptionFactory::create_select(
            OptionCollection::from_array([
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