<?php

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentFactory\UserLinkFactory;
use AC\Setting\ComponentFactory\UserProperty;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\ConditionalComponentFactoryCollection;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Type\PostTypeSlug;
use AC\Value\Formatter\Post\LastModifiedAuthor;

class LastModifiedAuthorFactory extends BaseColumnFactory
{

    private UserProperty $user_factory;

    private UserLinkFactory $user_link;

    private PostTypeSlug $post_type;

    public function __construct(
        ComponentFactoryRegistry $component_factory_registry,
        UserProperty $user_factory,
        UserLinkFactory $user_link,
        PostTypeSlug $post_type
    ) {
        parent::__construct($component_factory_registry);

        $this->user_factory = $user_factory;
        $this->user_link = $user_link;
        $this->post_type = $post_type;
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
        $factories->add($this->user_link->create($this->post_type));
    }

    protected function add_formatters(FormatterCollection $formatters, Config $config): void
    {
        $formatters->prepend(new LastModifiedAuthor());
    }

}