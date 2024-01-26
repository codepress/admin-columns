<?php

declare(strict_types=1);

namespace AC\Settings\Column;

<<<<<<< HEAD
use AC;
use AC\Setting\Component\Input\OptionFactory;
use AC\Setting\Component\OptionCollection;
use AC\Setting\SettingTrait;
=======
use AC\Expression\Specification;
use AC\Setting\Input;
use AC\Setting\OptionCollection;
>>>>>>> bf39a92dd4a8273b3c8a4ed1eb27b15114e9f4a2
use AC\Settings;

class CommentCount extends Settings\Column
{

    public const NAME = 'comment_status';

    public const STATUS_ALL = 'all';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_PENDING = 'moderated';
    public const STATUS_SPAM = 'spam';
    public const STATUS_TRASH = 'trash';

    public function __construct(Specification $conditionals = null)
    {
<<<<<<< HEAD
        $this->name = self::NAME;
        $this->label = __('Comment status', 'codepress-admin-columns');
        $this->description = __('Select which comment status you like to display.', 'codepress-admin-columns');
        $this->input = OptionFactory::create_select(
=======
        $input = Input\Option\Single::create_select(
>>>>>>> bf39a92dd4a8273b3c8a4ed1eb27b15114e9f4a2
            OptionCollection::from_array($this->get_comment_statuses()),
            self::STATUS_ALL
        );

        parent::__construct(
            'comment_status',
            __('Comment status', 'codepress-admin-columns'),
            __('Select which comment status you like to display.', 'codepress-admin-columns'),
            $input,
            $conditionals
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