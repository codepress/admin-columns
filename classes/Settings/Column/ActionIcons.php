<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Setting\Component\Input\OptionFactory;
use AC\Settings\Column;

// TODO implement formatter with '<span class="cpac_use_icons"></span>'

class ActionIcons extends Column
{

    public function __construct()
    {
        parent::__construct(
            __('Use icons?', 'codepress-admin-columns'),
            __('Use icons instead of text for displaying the actions.', 'codepress-admin-columns'),
            OptionFactory::create_toggle('use_icons')
        );
    }

}