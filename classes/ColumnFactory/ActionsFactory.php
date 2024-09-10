<?php

namespace AC\ColumnFactory;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentFactory;
use AC\Setting\ConditionalComponentFactoryCollection;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Message;

// TODO Allow only one
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
        parent::add_component_factories($factories);

        $factories->add(new ComponentFactory\ActionIcons());
    }

    protected function add_formatters(FormatterCollection $formatters, Config $config): void
    {
        parent::add_formatters($formatters, $config);

        $formatters->add(new Message('<span></span>'));
    }

}