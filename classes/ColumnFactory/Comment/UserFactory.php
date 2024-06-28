<?php

namespace AC\ColumnFactory\Comment;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\UserLink;
use AC\Setting\ComponentFactory\UserProperty;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Comment\UserId;

class UserFactory extends BaseColumnFactory
{

    private $user_property;

    private $user_link;

    public function __construct(
        ComponentFactoryRegistry $component_factory_registry,
        UserProperty $user_property,
        UserLink $user_link
    ) {
        parent::__construct($component_factory_registry);

        $this->user_property = $user_property;
        $this->user_link = $user_link;
    }

    protected function add_component_factories(Config $config): void
    {
        $this->add_component_factory($this->user_property);
        $this->add_component_factory($this->user_link);

        parent::add_component_factories($config);
    }

    protected function get_label(): string
    {
        return __('User', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-user';
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new UserId());

        return parent::get_formatters($components, $config, $formatters);
    }
}