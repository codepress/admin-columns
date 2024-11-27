<?php

declare(strict_types=1);

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentFactory\BeforeAfter;
use AC\Setting\ComponentFactory\UserLink;
use AC\Setting\ComponentFactory\UserProperty;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\ConditionalComponentFactoryCollection;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter;

class AuthorFactory extends BaseColumnFactory
{

    private UserProperty $user_factory;

    private BeforeAfter $before_after_factory;

    private UserLink $user_link;

    public function __construct(
        ComponentFactoryRegistry $component_factory_registry,
        UserProperty $user_factory,
        UserLink $user_link,
        BeforeAfter $before_after_factory
    ) {
        parent::__construct($component_factory_registry);

        $this->user_factory = $user_factory;
        $this->before_after_factory = $before_after_factory;
        $this->user_link = $user_link;
    }

    protected function add_component_factories(ConditionalComponentFactoryCollection $factories): void
    {
        $factories->add($this->user_factory)
                  ->add($this->user_link)
                  ->add($this->before_after_factory);
    }

    protected function add_formatters(FormatterCollection $formatters, Config $config): void
    {
        parent::add_formatters($formatters, $config);

        $formatters->prepend(new Formatter\Post\Author());
    }

    public function get_column_type(): string
    {
        return 'column-author_name';
    }

    public function get_label(): string
    {
        return __('Author', 'codepress-admin-columns');
    }

}
