<?php

namespace AC\ColumnFactory\Comment;

use AC\Column\BaseColumnFactory;
use AC\Formatter\Comment\Property;
use AC\FormatterCollection;
use AC\Setting\Config;

class AgentFactory extends BaseColumnFactory
{

    public function get_label(): string
    {
        return __('Agent', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-agent';
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);
        $formatters->add(new Property('comment_agent'));

        return $formatters;
    }

}