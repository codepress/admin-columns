<?php

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentFactory\UserLink;
use AC\Setting\ComponentFactory\UserProperty;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\ConditionalComponentFactoryCollection;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter\Post\LastModifiedAuthor;

class LastModifiedAuthorFactory extends BaseColumnFactory
{

    private $user_factory;

    private $user_link;

    public function __construct(
        ComponentFactoryRegistry $component_factory_registry,
        UserProperty $user_factory,
        UserLink $user_link
    ) {
        parent::__construct($component_factory_registry);

        $this->user_factory = $user_factory;
        $this->user_link = $user_link;
    }

    public function get_column_type(): string
    {
        return 'column-last_modified_author';
    }

    public function get_label(): string
    {
        return __('Last Modified Author', 'codepress-admin-columns');
    }

    protected function add_component_factories(ConditionalComponentFactoryCollection $factories): void
    {
        $factories->add($this->user_factory);
        $factories->add($this->user_link);
    }

    protected function add_formatters(FormatterCollection $formatters, Config $config): void
    {
        $formatters->prepend(new LastModifiedAuthor());
    }

}