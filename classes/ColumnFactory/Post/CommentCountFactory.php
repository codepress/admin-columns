<?php

declare(strict_types=1);

namespace AC\ColumnFactory\Post;

use AC\Column\BaseColumnFactory;
use AC\Setting\ComponentFactory\CommentStatus;
use AC\Setting\ComponentFactoryRegistry;
use AC\Setting\ConditionalComponentFactoryCollection;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter;

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

    protected function add_component_factories(ConditionalComponentFactoryCollection $factories): void
    {
        $factories->add($this->comment_status);
    }

    protected function add_formatters(FormatterCollection $formatters, Config $config): void
    {
        $formatters->add(new Formatter\Post\CommentCount((string)$config->get('comment_status')));
        $formatters->add(new Formatter\Post\CommentsForPostLink((string)$config->get('comment_status')));
    }

    public function get_column_type(): string
    {
        return 'column-comment_count';
    }

    public function get_label(): string
    {
        return __('Comment Count', 'codepress-admin-columns');
    }

}