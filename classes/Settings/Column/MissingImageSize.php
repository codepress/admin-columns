<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Component\Input\OptionFactory;
use AC\Setting\Component\OptionCollection;
use AC\Settings;

class MissingImageSize extends Settings\Column
{

    public function __construct(Specification $conditions = null)
    {
        parent::__construct(
            'include_missing_sizes',
            __('Include missing sizes?', 'codepress-admin-columns'),
            __('Include sizes that are missing an image file.', 'codepress-admin-columns'),
            OptionFactory::create_toggle(
                'include_missing_sizes',
                OptionCollection::from_array([
                    '1',
                    '',
                ], false),
                ''
            ),
            $conditions
        );
    }

}