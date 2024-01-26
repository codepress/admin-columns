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

class MissingImageSize extends Settings\Column
{

    public function __construct(Specification $conditions = null)
    {
<<<<<<< HEAD
        $this->name = 'include_missing_sizes';
        $this->label = __('Include missing sizes?', 'codepress-admin-columns');
        $this->description = __('Include sizes that are missing an image file.', 'codepress-admin-columns');
        $this->input = OptionFactory::create_toggle(
=======
        $input = AC\Setting\Input\Option\Single::create_toggle(
>>>>>>> bf39a92dd4a8273b3c8a4ed1eb27b15114e9f4a2
            OptionCollection::from_array([
                '1',
                '',
            ], false),
            ''
        );

        parent::__construct(
            'include_missing_sizes',
            __('Include missing sizes?', 'codepress-admin-columns'),
            __('Include sizes that are missing an image file.', 'codepress-admin-columns'),
            $input,
            $conditions
        );
    }

}