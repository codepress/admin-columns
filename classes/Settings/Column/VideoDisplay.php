<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Component\Input\OptionFactory;
use AC\Setting\Component\OptionCollection;
use AC\Settings;

class VideoDisplay extends Settings\Setting
{

    public function __construct(Specification $conditions = null)
    {
        parent::__construct(
            OptionFactory::create_select(
                'video_display',
                OptionCollection::from_array([
                    'embed' => __('Embed', 'codepress-admin-columns'),
                    'modal' => __('Pop Up', 'codepress-admin-columns'),
                ]),
                'embed'
            ),
            __('Display', 'codepress-admin-columns'),
            null,
            $conditions
        );
    }

}