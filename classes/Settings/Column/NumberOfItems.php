<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Input\Number;
use AC\Settings;

class NumberOfItems extends Settings\Column
{

    public function __construct(Specification $specification = null)
    {
        parent::__construct(
            'number_of_items',
            __('Number of Items', 'codepress-admin-columns'),
            sprintf(
                '%s <em>%s</em>',
                __('Maximum number of items', 'codepress-admin-columns'),
                __('Leave empty for no limit', 'codepress-admin-columns')
            ),
            Number::create_single_step(0, null, 10),
            $specification
        );
    }

}