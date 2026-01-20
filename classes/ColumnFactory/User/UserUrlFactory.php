<?php

declare(strict_types=1);

namespace AC\ColumnFactory\User;

use AC;
use AC\Column\BaseColumnFactory;
use AC\FormatterCollection;
use AC\Setting\Config;

class UserUrlFactory extends BaseColumnFactory
{

    public function get_label(): string
    {
        return __('Website', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-user_url';
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        return parent::get_formatters($config)
                     ->add(new AC\Formatter\User\Property('user_url'))
                     ->add(new AC\Formatter\Linkable(null, '_blank'));
    }

}