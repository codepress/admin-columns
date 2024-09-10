<?php

namespace AC\ColumnFactory\User;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentFactory;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\ConditionalComponentFactoryCollection;
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
        $formatters->add(new Formatter\User\PostCount($post_type, $post_status));
    }

    protected function add_component_factories(ConditionalComponentFactoryCollection $factories): void
    {
        $factories->add($this->post_type);
        $factories->add($this->post_status);
    }

}