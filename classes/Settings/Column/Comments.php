<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Component\Input\OptionFactory;
use AC\Setting\Component\OptionCollection;
use AC\Setting\Formatter;
use AC\Setting\Type\Value;
use AC\Settings;

class Comments extends Settings\Control implements Formatter
{

    public const NAME = 'comment_status';

    public const STATUS_ALL = 'all';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_PENDING = 'moderated';
    public const STATUS_SPAM = 'spam';
    public const STATUS_TRASH = 'trash';

    protected $comment_status;

    public function __construct(string $comment_status, Specification $specification = null)
    {
        parent::__construct(
            OptionFactory::create_select(
                'comment_status',
                OptionCollection::from_array($this->get_comment_statuses()),
                $comment_status
            ),
            __('Comment status', 'codepress-admin-columns'),
            __('Select which comment status you like to display.', 'codepress-admin-columns'),
            $specification
        );
        $this->comment_status = $comment_status;
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

    private function format_value_with_count(Value $value): Value
    {
        $counts = wp_count_comments($value->get_id());
        $status = $this->comment_status;

        $count = $counts->{$status} ?? 0;

        return $value->with_value(
            (int)$count
        );
    }

    private function format_value_with_url(Value $value): Value
    {
        return $value->with_value(
            sprintf(
                '<a href="%s">%s</a>',
                add_query_arg([
                    'p'              => $value->get_id(),
                    'comment_status' => $this->comment_status,
                ],
                    admin_url('edit-comments.php')
                ),
                $value
            )
        );
    }

    public function format(Value $value): Value
    {
        return $this->format_value_with_url(
            $this->format_value_with_count($value)
        );
    }

}