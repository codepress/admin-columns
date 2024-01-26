<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC;
<<<<<<< HEAD
use AC\Setting\Component\Input\OptionFactory;
use AC\Setting\Component\OptionCollection;
=======
use AC\Expression\Specification;
use AC\Setting\OptionCollection;
>>>>>>> bf39a92dd4a8273b3c8a4ed1eb27b15114e9f4a2
use AC\Settings;

class VideoDisplay extends Settings\Column
{

    public function __construct(Specification $conditions = null)
    {
<<<<<<< HEAD
        $this->name = 'video_display';
        $this->label = __('Display', 'codepress-admin-columns');
        $this->input = OptionFactory::create_select(
=======
        $input = AC\Setting\Input\Option\Single::create_select(
>>>>>>> bf39a92dd4a8273b3c8a4ed1eb27b15114e9f4a2
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