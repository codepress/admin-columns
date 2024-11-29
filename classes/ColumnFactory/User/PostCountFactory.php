<?php

namespace AC\ColumnFactory\User;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentFactory;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\ConditionalComponentFactoryCollection;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Extended\Posts;
use AC\Value\Formatter;

class PostCountFactory extends BaseColumnFactory
{

    private ComponentFactory\PostTypeFactory $post_type_factory;

    private ComponentFactory\PostStatus $post_status;

    public function __construct(
        ComponentFactoryRegistry $component_factory_registry,
        ComponentFactory\PostStatus $post_status,
        ComponentFactory\PostTypeFactory $post_type_factory
    ) {
        parent::__construct($component_factory_registry);

        $this->post_status = $post_status;
        $this->post_type_factory = $post_type_factory;
    }

    public function get_label(): string
    {
        return __('Post Count', 'codepress-admin-columns');
    }

    public function get_column_type(): string
    {
        return 'column-user_postcount';
    }

    protected function add_formatters(FormatterCollection $formatters, Config $config): void
    {
        $formatters->add(
            new Formatter\User\PostCount(
                new Posts($this->get_post_types($config), $config->get('post_status'))
            )
        );
    }

    protected function add_component_factories(ConditionalComponentFactoryCollection $factories): void
    {
        $factories->add($this->post_type_factory->create(true));
        $factories->add($this->post_status);
    }

    private function get_post_types(Config $config): ?array
    {
        $post_type = $config->get('post_type');

        if (in_array($post_type, ['any', ''], true)) {
            return null;
        }

        return [$post_type];
    }

}