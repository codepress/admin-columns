<?php

declare(strict_types=1);

namespace AC\ColumnFactory\Comment;

use AC;
use AC\Column\BaseColumnFactory;
use AC\FormatterCollection;
use AC\Setting\Config;

class ApprovedFactory extends BaseColumnFactory
{

    public function get_label(): string
    {
        return __('Approved', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-approved';
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = new FormatterCollection([
            new AC\Formatter\Comment\Property('comment_approved'),
            new AC\Formatter\YesNoIcon(),
        ]);

        return $formatters->merge(parent::get_formatters($config));
    }

}