<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Component\Input\OptionFactory;
use AC\Setting\Component\OptionCollection;
use AC\Settings;

class CommentCount extends Settings\Setting
{

    public const NAME = 'comment_status';

    public const STATUS_ALL = 'all';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_PENDING = 'moderated';
    public const STATUS_SPAM = 'spam';
    public const STATUS_TRASH = 'trash';

    public function __construct(string $comment_status = null, Specification $specification = null)
    {
        parent::__construct(
            __('Comment status', 'codepress-admin-columns'),
            __('Select which comment status you like to display.', 'codepress-admin-columns'),
            OptionFactory::create_select(
                'comment_status',
                OptionCollection::from_array($this->get_comment_statuses()),
                $comment_status ?: self::STATUS_ALL
            ),
            $specification
        );
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