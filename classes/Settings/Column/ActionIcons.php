<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Setting\Component\Input\OptionFactory;
use AC\Setting\Component\OptionCollectionFactory\ToggleOptionCollection;
use AC\Settings\Column;

class ActionIcons extends Column
{

    public function __construct()
    {
        parent::__construct(
            'use_icons',
            __('Use icons?', 'codepress-admin-columns'),
            __('Use icons instead of text for displaying the actions.', 'codepress-admin-columns'),
            OptionFactory::create_toggle(
                (new ToggleOptionCollection())->create()
            )
        );
    }

}