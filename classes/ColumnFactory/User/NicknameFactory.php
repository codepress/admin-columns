<?php

namespace AC\ColumnFactory\User;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentFactory\UserLink;
use AC\Setting\BaseSettingsBuilder;
use AC\Setting\ConditionalComponentFactoryCollection;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter;

class NicknameFactory extends BaseColumnFactory
{

    private UserLink $user_link;

    public function __construct(BaseSettingsBuilder $base_settings_builder, UserLink $user_link)
    {
        parent::__construct($base_settings_builder);

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

    protected function add_formatters(FormatterCollection $formatters, Config $config): void
    {
        $formatters->prepend(new Formatter\User\Meta('nickname'));
    }

    protected function add_component_factories(ConditionalComponentFactoryCollection $factories): void
    {
        $factories->add($this->user_link);
    }

}