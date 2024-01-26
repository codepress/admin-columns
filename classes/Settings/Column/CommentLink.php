<?php

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\ArrayImmutable;
<<<<<<< HEAD
use AC\Setting\Component\OptionCollection;
use AC\Setting\SettingTrait;
=======
use AC\Setting\Formatter;
use AC\Setting\Input;
use AC\Setting\OptionCollection;
>>>>>>> bf39a92dd4a8273b3c8a4ed1eb27b15114e9f4a2
use AC\Setting\Type\Value;
use AC\Settings;

class CommentLink extends Settings\Column implements Formatter
{

    public function __construct(Specification $conditions = null)
    {
<<<<<<< HEAD
        $this->name = 'comment_link_to';
        $this->label = __('Link To', 'codepress-admin-columns');
        $this->input = Input\Element\Multiple::create_select(
=======
        $input = Input\Option\Multiple::create_select(
>>>>>>> bf39a92dd4a8273b3c8a4ed1eb27b15114e9f4a2
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