<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Component\Input\Number;
use AC\Settings;

class NumberOfItems extends Settings\Control
{

    public function __construct(Specification $specification = null)
    {
        parent::__construct(
            Number::create_single_step('number_of_items', 0, null, 10),
            __('Number of Items', 'codepress-admin-columns'),
            sprintf(
                '%s <em>%s</em>',
                __('Maximum number of items', 'codepress-admin-columns'),
                __('Leave empty for no limit', 'codepress-admin-columns')
            ),
            $specification
        );
    }

}