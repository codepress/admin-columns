<?php

namespace AC\ColumnFactory\Comment;

use AC\Column\ColumnFactory;
use AC\Setting\BaseSettingsBuilder;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\UserLink;
use AC\Setting\ComponentFactory\UserProperty;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Comment\UserId;

class UserFactory extends ColumnFactory
{

    private UserProperty $user_property;

    private UserLink $user_link;

    public function __construct(
        BaseSettingsBuilder $base_settings_builder,
        UserProperty $user_property,
        UserLink $user_link
    ) {
        parent::__construct($base_settings_builder);

        $this->user_property = $user_property;
        $this->user_link = $user_link;
    }

    protected function get_settings(Config $config): ComponentCollection
    {
        return new ComponentCollection([
            $this->user_property->create($config),
            $this->user_link->create($config),
        ]);
    }

    public function get_label(): string
    {
        return __('User', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-user';
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $formatters = parent::get_formatters($config);
        $formatters->prepend(new UserId());

        return $formatters;
    }
}