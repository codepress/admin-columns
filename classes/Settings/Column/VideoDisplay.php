<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollection;
use AC\Settings;

class VideoDisplay extends Settings\Control
{

    public function __construct(string $video_display, Specification $conditions = null)
    {
        parent::__construct(
            OptionFactory::create_select(
                'video_display',
                OptionCollection::from_array([
                    'embed' => __('Embed', 'codepress-admin-columns'),
                    'modal' => __('Pop Up', 'codepress-admin-columns'),
                ]),
                $video_display
            ),
            __('Display', 'codepress-admin-columns'),
            null,
            $conditions
        );
    }

}