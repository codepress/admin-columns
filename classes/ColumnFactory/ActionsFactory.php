<?php

namespace AC\ColumnFactory;

use AC;
use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Message;

class ActionsFactory extends BaseColumnFactory
{

    public function get_label(): string
    {
        return __('Actions', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-actions';
    }

    protected function get_settings(Config $config): AC\Setting\ComponentCollection
    {
        return new AC\Setting\ComponentCollection([
            (new ComponentFactory\ActionIcons())->create($config),
        ]);
    }

    protected function add_formatters(FormatterCollection $formatters, Config $config): void
    {
        $formatters->add(new Message('<span class="cpac_use_icons"></span>'));
    }

}