<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC;
use AC\Setting\OptionCollection;
use AC\Settings\Column;

class ActionIcons extends Column
{

    use AC\Setting\SettingTrait;

    public function __construct()
    {
        // TODO remove parent::__construct
        parent::__construct();

        $this->name = 'use_icons';
        $this->label = __('Use icons?', 'codepress-admin-columns');
        $this->description = __('Use icons instead of text for displaying the actions.', 'codepress-admin-columns');
        $this->input = AC\Setting\Input\Option\Single::create_toggle(
            OptionCollection::from_array(['1', ''], false),
            ''
        );
    }

}