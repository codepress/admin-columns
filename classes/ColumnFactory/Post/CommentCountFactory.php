<?php

declare(strict_types=1);

namespace AC\ColumnFactory\Post;

use AC;
use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentCollection;
use AC\Setting\ComponentFactory\CommentStatus;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;

class CommentCountFactory extends BaseColumnFactory
{

    private $comment_status;

    public function __construct(
        ComponentFactoryRegistry $component_factory_registry,
        CommentStatus $comment_status
    ) {
        parent::__construct($component_factory_registry);

        $this->comment_status = $comment_status;
    }

    protected function add_component_factories(Config $config): void
    {
        parent::add_component_factories($config);

        $this->add_component_factory($this->comment_status);
    }

    public function get_column_type(): string
    {
        return 'column-comment_count';
    }

    public function get_label(): string
    {
        return __('Comment Count', 'codepress-admin-columns');
    }

    protected function get_formatters(
        ComponentCollection $components,
        Config $config,
        FormatterCollection $formatters
    ): FormatterCollection {
        $formatters->add(new AC\Value\Formatter\Post\CommentCount((string)$config->get('comment_status')));
        $formatters->add(new AC\Value\Formatter\Post\CommentsForPostLink((string)$config->get('comment_status')));

        return parent::get_formatters($components, $config, $formatters);
    }

}