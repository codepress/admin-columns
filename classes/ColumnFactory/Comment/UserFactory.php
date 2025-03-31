<?php

namespace AC\ColumnFactory\Comment;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentFactory\UserLink;
use AC\Setting\ComponentFactory\UserProperty;
use AC\Setting\BaseSettingsBuilder;
use AC\Setting\ConditionalComponentFactoryCollection;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Comment\UserId;

class UserFactory extends BaseColumnFactory
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

    protected function add_component_factories(ConditionalComponentFactoryCollection $factories): void
    {
        $factories->add($this->user_property);
        $factories->add($this->user_link);
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