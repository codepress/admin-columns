<?php

namespace AC\Settings\Column;

use AC\Expression\Specification;
use AC\Setting\Component\Input;
use AC\Setting\Component\OptionCollection;
use AC\Setting\Config;
use AC\Setting\Formatter;
use AC\Setting\Type\Value;
use AC\Settings;

class CommentLink extends Settings\Column implements Formatter
{

    public function __construct(string $comment_link = null, Specification $specification = null)
    {
        parent::__construct(
            __('Link To', 'codepress-admin-columns'),
            '',
            Input\OptionFactory::create_select(
                'comment_link_to',
                OptionCollection::from_array(
                    [
                        ''             => __('None'),
                        'view_comment' => __('View Comment', 'codepress-admin-columns'),
                        'edit_comment' => __('Edit Comment', 'codepress-admin-columns'),
                    ]
                ),
                $comment_link ?: '',
                null,
                true
            ),
            $specification
        );
    }

    public function format(Value $value, Config $options): Value
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