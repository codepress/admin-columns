<?php

namespace AC\ColumnFactory\Comment;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentFactory\UserLink;
use AC\Setting\ComponentFactory\UserProperty;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\ConditionalComponentFactoryCollection;
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

    protected function add_formatters(FormatterCollection $formatters, Config $config): void
    {
        $formatters->prepend(new UserId());
    }
}