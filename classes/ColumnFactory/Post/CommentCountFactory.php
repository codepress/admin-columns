<?php

declare(strict_types=1);

namespace AC\ColumnFactory\Post;

use AC;
use AC\Column\BaseColumnFactory;
use AC\Setting\DefaultSettingsBuilder;
use AC\Setting\ComponentFactory\CommentStatus;
use AC\Setting\Config;
use AC\Setting\FormatterCollection;
use AC\Value\Formatter;

class CommentCountFactory extends BaseColumnFactory
{

    private CommentStatus $comment_status;

    public function __construct(
        DefaultSettingsBuilder $default_settings_builder,
        CommentStatus $comment_status
    ) {
        parent::__construct($default_settings_builder);

        $this->comment_status = $comment_status;
    }

    protected function get_settings(Config $config): AC\Setting\ComponentCollection
    {
        return new AC\Setting\ComponentCollection([
            $this->comment_status->create($config),
        ]);
    }

    protected function get_formatters(Config $config): FormatterCollection
    {
        $status = (string)$config->get('comment_status', 'all');

        return parent::get_formatters($config)
                     ->add(new Formatter\Post\CommentCount($status))
                     ->add(new Formatter\Post\CommentsForPostLink($status));
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