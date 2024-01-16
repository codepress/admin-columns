<?php

declare(strict_types=1);

namespace AC\Settings\Column;

use AC;
use AC\Setting\Input;
use AC\Setting\OptionCollection;
use AC\Setting\SettingTrait;
use AC\Settings;
use AC\Expression\Specification;

class CommentCount extends Settings\Column
{

    use SettingTrait;

    public const NAME = 'comment_status';

    public const STATUS_ALL = 'all';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_PENDING = 'moderated';
    public const STATUS_SPAM = 'spam';
    public const STATUS_TRASH = 'trash';

    public function __construct(AC\Column $column, Specification $conditionals = null)
    {
        $this->name = self::NAME;
        $this->label = __('Comment status', 'codepress-admin-columns');
        $this->description = __('Select which comment status you like to display.', 'codepress-admin-columns');
        $this->input = Input\Option\Single::create_select(
            OptionCollection::from_array($this->get_comment_statuses()),
            self::STATUS_ALL
        );

        parent::__construct($column, $conditionals);
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