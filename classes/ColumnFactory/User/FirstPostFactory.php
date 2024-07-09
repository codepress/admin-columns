<?php

namespace AC\ColumnFactory\User;

use AC;
use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;

class FirstPostFactory extends BaseColumnFactory
{

    private $post_type;

    private $post_status;

    private $post_property;

    private $post_link;

    public function __construct(
        ComponentFactoryRegistry $component_factory_registry,
        ComponentFactory\PostType $post_type,
        ComponentFactory\PostStatus $post_status,
        ComponentFactory\PostProperty $post_property,
        ComponentFactory\PostLink $post_link
    ) {
        parent::__construct($component_factory_registry);

        $this->post_type = $post_type;
        $this->post_status = $post_status;
        $this->post_property = $post_property;
        $this->post_link = $post_link;
    }

    protected function add_component_factories(Config $config): void
    {
        parent::add_component_factories($config);

        $this->add_component_factory($this->post_type);
        $this->add_component_factory($this->post_status);
        $this->add_component_factory($this->post_property);
        $this->add_component_factory($this->post_link);
    }

    public function get_label(): string
    {
        return __('First Post', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-first_post';
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $post_type = $config->has('post_type') ? (array)$config->get('post_type') : null;
        $post_status = $config->has('post_status') ? (array)$config->get('post_status') : null;

        $formatters->add(new AC\Value\Formatter\User\FirstPost($post_type, $post_status));

        return parent::get_formatters($components, $config, $formatters);
    }

}