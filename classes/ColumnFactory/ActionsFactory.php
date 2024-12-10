<?php

namespace AC\ColumnFactory;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentFactory;
use AC\Setting\ConditionalComponentFactoryCollection;
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

    protected function add_component_factories(ConditionalComponentFactoryCollection $factories): void
    {
        $factories->add(new ComponentFactory\ActionIcons());
    }

    protected function add_formatters(FormatterCollection $formatters, Config $config): void
    {
        $formatters->add(new Message('<span class="cpac_use_icons"></span>'));
    }

}