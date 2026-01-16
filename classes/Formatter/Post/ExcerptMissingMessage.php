<?php

declare(strict_types=1);

namespace AC\Formatter\Post;

use AC\Formatter;
use AC\Type\Value;

class ExcerptMissingMessage implements Formatter
{

    private bool $tooltip;

    public function __construct(bool $tooltip = true)
    {
        $this->tooltip = $tooltip;
    }

    public function format(Value $value): Value
    {
        $excerpt = $value->get_value();
        if ( ! $excerpt) {
            return $value;
        }

        $post_excerpt = get_post((int)$value->get_id())->post_excerpt ?? null;

        if ($post_excerpt) {
            return $value;
        }

        if ($this->tooltip) {
            return $value->with_value(
                ac_helper()->html->tooltip(
                    ac_helper()->icon->dashicon(['icon' => 'media-text', 'class' => 'gray']),
                    sprintf(
                        '%s %s',
                        __('Excerpt is missing.', 'codepress-admin-columns'),
                        __('Current excerpt is generated from the content.', 'codepress-admin-columns')
                    )
                ) . $excerpt
            );
        }

        return $value->with_value(
            sprintf(
                '[%s] %s',
                __('auto-generated', 'codepress-admin-columns'),
                $excerpt
            )
        );
    }

}