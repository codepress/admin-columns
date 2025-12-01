<?php

declare(strict_types=1);

namespace AC\Setting\ComponentFactory;

use AC\Expression\Specification;
use AC\Setting\Component;
use AC\Setting\ComponentBuilder;
use AC\Setting\Config;
use AC\Setting\Control\Input;
use AC\Setting\Control\Input\OptionFactory;
use AC\Setting\Control\OptionCollection;

final class CommentStatus extends BaseComponentFactory
{

    public const NAME = 'comment_status';

    public const STATUS_ALL = 'all';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_PENDING = 'moderated';
    public const STATUS_SPAM = 'spam';
    public const STATUS_TRASH = 'trash';

    protected function get_label(Config $config): ?string
    {
        return __('Comment Status', 'codepress-admin-columns');
    }

    protected function get_input(Config $config): ?Input
    {
        return OptionFactory::create_select(
            'comment_status',
            OptionCollection::from_array($this->get_comment_statuses()),
            $config->get('comment_status') ?: 'all'
        );
    }

    public function create(Config $config, ?Specification $conditions = null): Component
    {
        $builder = (new ComponentBuilder())
            ->set_label(__('Comment Status', 'codepress-admin-columns'))
            ->set_input(
                OptionFactory::create_select(
                    'comment_status',
                    OptionCollection::from_array($this->get_comment_statuses()),
                    $config->get('comment_status') ?: 'all'
                )
            );

        if ($conditions) {
            $builder->set_conditions($conditions);
        }

        return $builder->build();
    }

    protected function get_comment_statuses(): array
    {
        $options = [
            self::STATUS_APPROVED => __('Approved', 'codepress-admin-columns'),
            self::STATUS_PENDING  => __('Pending', 'codepress-admin-columns'),
            self::STATUS_SPAM     => __('Spam', 'codepress-admin-columns'),
            self::STATUS_TRASH    => __('Trash', 'codepress-admin-columns'),
        ];

        natcasesort($options);

        // First
        return [self::STATUS_ALL => __('Total', 'codepress-admin-columns')] + $options;
    }

}