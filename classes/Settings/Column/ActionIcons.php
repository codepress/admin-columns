<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC;
<<<<<<< HEAD
use AC\Setting\Component\Input\OptionFactory;
use AC\Setting\Component\OptionCollectionFactory\ToggleOptionCollection;
=======
use AC\Setting\OptionCollection;
>>>>>>> bf39a92dd4a8273b3c8a4ed1eb27b15114e9f4a2
use AC\Settings\Column;

class ActionIcons extends Column
{

    public function __construct()
    {
<<<<<<< HEAD
        $this->name = 'use_icons';
        $this->label = __('Use icons?', 'codepress-admin-columns');
        $this->description = __('Use icons instead of text for displaying the actions.', 'codepress-admin-columns');
        $this->input = OptionFactory::create_toggle(
            (new ToggleOptionCollection())->create()
=======
        parent::__construct(
            'use_icons',
            __('Use icons?', 'codepress-admin-columns'),
            __('Use icons instead of text for displaying the actions.', 'codepress-admin-columns'),
            AC\Setting\Input\Option\Single::create_toggle(
                OptionCollection::from_array(['1', ''], false),
                ''
            )
>>>>>>> bf39a92dd4a8273b3c8a4ed1eb27b15114e9f4a2
        );
    }

}