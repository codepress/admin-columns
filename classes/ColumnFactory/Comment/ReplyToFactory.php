<?php

declare(strict_types=1);

namespace AC\ColumnFactory\Comment;

use AC\Column\BaseColumnFactory;
use AC\Formatter\Comment\ParentId;
use AC\Formatter\Comment\ReplyToLink;
use AC\FormatterCollection;
use AC\Setting\Config;

class ReplyToFactory extends BaseColumnFactory
{

    public function get_label(): string
    {
        return __('In Reply To', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-reply_to';
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);
        $formatters->add(new ParentId());
        $formatters->add(new ReplyToLink());

        return $formatters;
    }

}