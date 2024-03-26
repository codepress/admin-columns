<?php

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\UserLink;
use AC\Setting\ComponentFactory\UserProperty;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;
use AC\Setting\Formatter\Post\LastModifiedAuthor;
use AC\Setting\FormatterCollection;

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

    protected function add_component_factories(): void
    {
        parent::add_component_factories();

        $this->add_component_factory($this->user_factory);
        $this->add_component_factory($this->user_link);
    }

    public function get_type(): string
    {
        return 'column-last_modified_author';
    }

    protected function get_label(): string
    {
        return __('Last Modified Author', 'codepress-admin-columns');
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new LastModifiedAuthor());

        return parent::get_formatters($components, $config, $formatters);
    }

}