<?php

namespace AC\ColumnFactory\User;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\UserLink;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter;

class NicknameFactory extends BaseColumnFactory
{

    private $user_link;

    public function __construct(
        ComponentFactoryRegistry $component_factory_registry,
        UserLink $user_link
    ) {
        parent::__construct($component_factory_registry);
        $this->user_link = $user_link;
    }

    protected function add_component_factories(Config $config): void
    {
        parent::add_component_factories($config);

        $this->add_component_factory($this->user_link);
    }

    protected function get_label(): string
    {
        return __('Nickname', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-nickname';
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new Formatter\User\Meta('nickname'));

        return parent::get_formatters($components, $config, $formatters);
    }

}