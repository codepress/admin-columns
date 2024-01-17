<?php

namespace AC\Settings\Column;

use AC;
use AC\Expression\Specification;
use AC\Setting\ArrayImmutable;
use AC\Setting\Input;
use AC\Setting\OptionCollection;
use AC\Setting\Type\Value;
use AC\Settings;

class CommentLink extends Settings\Column implements AC\Setting\Formatter
{

    public function __construct(Specification $conditions = null)
    {
        $input = Input\Option\Multiple::create_select(
            OptionCollection::from_array(
                [
                    ''             => __('None'),
                    'view_comment' => __('View Comment', 'codepress-admin-columns'),
                    'edit_comment' => __('Edit Comment', 'codepress-admin-columns'),
                ]
            )
        );

        parent::__construct(
            'comment_link_to',
            __('Link To', 'codepress-admin-columns'),
            '',
            $input,
            $conditions
        );
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