<?php

namespace AC\Settings\Column;

use AC;
use AC\Setting\ArrayImmutable;
use AC\Setting\Input;
use AC\Setting\OptionCollection;
use AC\Setting\SettingTrait;
use AC\Setting\Type\Value;
use AC\Settings;
use ACP\Expression\Specification;

class CommentLink extends Settings\Column implements AC\Setting\Formatter
{

    use SettingTrait;

    public function __construct(AC\Column $column, Specification $conditions = null)
    {
        $this->name = 'comment_link_to';
        $this->label = __('Link To', 'codepress-admin-columns');
        $this->input = Input\Option\Multiple::create_select(
            OptionCollection::from_array(
                [
                    ''             => __('None'),
                    'view_comment' => __('View Comment', 'codepress-admin-columns'),
                    'edit_comment' => __('Edit Comment', 'codepress-admin-columns'),
                ]
            )
        );

        parent::__construct($column, $conditions);
    }

    public function format(Value $value, ArrayImmutable $options): Value
    {
        $link = null;

        switch ($options->get('comment_link_to')) {
            case 'view_comment' :
                $link = get_comment_link($value->get_id());

                break;
            case 'edit_comment' :
                $comment = get_comment($value->get_id());

                $link = $comment
                    ? get_edit_comment_link($comment)
                    : false;

                break;
        }

        return $link
            ? $value->with_value(ac_helper()->html->link($link, $value->get_value()))
            : $value;
    }

}