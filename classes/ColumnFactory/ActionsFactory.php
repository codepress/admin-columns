<?php

namespace AC\ColumnFactory;

use AC;
use AC\Column\BaseColumnFactory;
use AC\Formatter\Message;
use AC\FormatterCollection;
use AC\Setting\ComponentFactory;
use AC\Setting\Config;

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

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);

        if ($config->get('use_icons', 'off') === 'on') {
            return $formatters->add(new Message('<span class="cpac_use_icons"></span>'));
        }

        return $formatters;
    }

}