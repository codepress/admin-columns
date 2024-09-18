<?php

namespace AC\ColumnFactory\User;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentFactory;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\ConditionalComponentFactoryCollection;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter;

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

    public function get_label(): string
    {
        return __('First Post', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-first_post';
    }

    protected function add_formatters(FormatterCollection $formatters, Config $config): void
    {
        $post_type = $config->has('post_type') ? (array)$config->get('post_type') : null;
        $post_status = $config->has('post_status') ? (array)$config->get('post_status') : null;

        $formatters->add(new Formatter\User\FirstPost($post_type, $post_status));
    }

    protected function add_component_factories(ConditionalComponentFactoryCollection $factories): void
    {
        $factories->add($this->post_type);
        $factories->add($this->post_status);
        $factories->add($this->post_property);
        $factories->add($this->post_link);
    }

}