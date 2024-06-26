<?php

namespace AC\ColumnFactory\User;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter;

class PostCountFactory extends BaseColumnFactory
{

    private $post_type;

    private $post_status;

    public function __construct(
        ComponentFactoryRegistry $component_factory_registry,
        ComponentFactory\PostType $post_type,
        ComponentFactory\PostStatus $post_status
    ) {
        parent::__construct($component_factory_registry);

        $this->post_type = $post_type;
        $this->post_status = $post_status;
    }

    protected function add_component_factories(Config $config): void
    {
        parent::add_component_factories($config);

        $this->add_component_factory($this->post_type);
        $this->add_component_factory($this->post_status);
    }

    protected function get_label(): string
    {
        return __('Post Count', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-user_postcount';
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $post_type = $config->has('post_type') ? (string)$config->get('post_type') : 'any';
        $post_status = $config->has('post_status') ? (array)$config->get('post_status') : null;

        $formatters->add(new Formatter\User\PostCount($post_type, $post_status));

        return parent::get_formatters($components, $config, $formatters);
    }

}