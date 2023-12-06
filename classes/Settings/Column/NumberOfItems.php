<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Column;
use AC\Setting\Input\Number;
use AC\Setting\SettingTrait;
use AC\Settings;
use ACP\Expression\Specification;

class NumberOfItems extends Settings\Column
{

    use SettingTrait;

    public function __construct(Column $column, Specification $specification = null)
    {
        $this->name = 'number_of_items';
        $this->label = __('Number of Items', 'codepress-admin-columns');
        $this->description = __('Maximum number of items', 'codepress-admin-columns') . '<em>' . __(
                'Leave empty for no limit',
                'codepress-admin-columns'
            ) . '</em>';
        $this->input = Number::create_single_step(0, null, 10);

        parent::__construct($column, $specification);
    }

}