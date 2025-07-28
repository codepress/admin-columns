<?php

declare(strict_types=1);

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Post\DiscussionStatus;

class DiscussionFactory extends BaseColumnFactory
{

    public function get_column_type(): string
    {
        return 'column-discussion_status';
    }

    public function get_label(): string
    {
        return __('Discussion');
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        return parent::get_formatters($config)
                     ->add(new DiscussionStatus());
    }

}