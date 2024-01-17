<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC;
use AC\Setting\OptionCollection;
use AC\Settings\Column;

class ActionIcons extends Column
{

    public function __construct()
    {
        parent::__construct(
            'use_icons',
            __('Use icons?', 'codepress-admin-columns'),
            __('Use icons instead of text for displaying the actions.', 'codepress-admin-columns'),
            AC\Setting\Input\Option\Single::create_toggle(
                OptionCollection::from_array(['1', ''], false),
                ''
            )
        );
    }

}