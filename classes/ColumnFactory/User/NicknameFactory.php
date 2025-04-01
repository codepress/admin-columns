<?php

namespace AC\ColumnFactory\User;

use AC\Column\ColumnFactory;
use AC\Setting\DefaultSettingsBuilder;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\UserLink;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter;

class NicknameFactory extends ColumnFactory
{

    private UserLink $user_link;

    public function __construct(DefaultSettingsBuilder $default_settings_builder, UserLink $user_link)
    {
        parent::__construct($default_settings_builder);

        $this->user_link = $user_link;
    }

    public function get_label(): string
    {
        return __('Nickname', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-nickname';
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        return parent::get_formatters($config)->prepend(new Formatter\User\Meta('nickname'));
    }

    protected function get_settings(Config $config): ComponentCollection
    {
        return new ComponentCollection([
            $this->user_link->create($config),
        ]);
    }

}