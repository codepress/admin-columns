<?php

declare(strict_types=1);

namespace AC\Formatter\Post;

use AC\Exception\ValueNotFoundException;
use AC\Formatter;
use AC\Helper;
use AC\Type\Value;
use WP_Post;

class PostTitle implements Formatter
{

    private bool $use_file_name_for_attachments;

    public function __construct(bool $use_file_name_for_attachments = true)
    {
        $this->use_file_name_for_attachments = $use_file_name_for_attachments;
    }

    public function format(Value $value): Value
    {
        $post = get_post($value->get_id());

        if ( ! $post) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        return $value->with_value(
            $this->get_title($post)
        );
    }

    private function get_title(WP_Post $post): string
    {
        if ($this->use_file_name_for_attachments && 'attachment' === $post->post_type) {
            return Helper\Image::create()->get_file_name($post->ID) ?: '';
        }

        return get_the_title($post);
    }

}