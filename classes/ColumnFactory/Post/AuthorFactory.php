<?php

declare(strict_types=1);

namespace AC\ColumnFactory\Post;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\BeforeAfter;
use AC\Setting\ComponentFactory\UserProperty;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Formatter;

class AuthorFactory extends ColumnFactory
{

    private $user_factory;

    private $before_after_factory;

    public function __construct(
        ComponentFactoryRegistry $component_factory_registry,
        UserProperty $user_factory,
        BeforeAfter $before_after_factory
    ) {
        parent::__construct($component_factory_registry);

        $this->user_factory = $user_factory;
        $this->before_after_factory = $before_after_factory;
    }

    protected function add_component_factories(): void
    {
        parent::add_component_factories();

        $this->add_component_factory($this->user_factory);
        $this->add_component_factory($this->before_after_factory);
    }

    public function get_type(): string
    {
        return 'column-author_name';
    }

    protected function get_label(): string
    {
        return __('Author', 'codepress-admin-columns');
    }

    protected function get_formatters(ComponentCollection $components): array
    {
        return array_merge([
            new Formatter\Post\Author(),
        ],
            parent::get_formatters($components)
        );
    }

}
