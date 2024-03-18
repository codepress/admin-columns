<?php

declare(strict_types=1);

namespace AC\ColumnFactory\Post;

use AC\Column\ColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\StringLimit;
use AC\Setting\ComponentFactoryRegistry;

class CommentFactory extends ColumnFactory
{

    private $string_limit_factory;

    public function __construct(
        ComponentFactoryRegistry $component_factory_registry,
        StringLimit $string_limit_factory
    ) {
        parent::__construct($component_factory_registry);

        $this->string_limit_factory = $string_limit_factory;
    }

    protected function add_component_factories(): void
    {
        parent::add_component_factories();

        $this->add_component_factory($this->string_limit_factory);
    }

    public function get_type(): string
    {
        return 'column-comment_count';
    }

    protected function get_label(): string
    {
        return __('Comment Count', 'codepress-admin-columns');
    }

    protected function get_formatters(ComponentCollection $components): array
    {
        return array_merge(
            [
                //new Formatter\Post\HasCommentStatus('open'),
            ],
            parent::get_formatters($components)
        );
    }

    // TODO test formatter

}