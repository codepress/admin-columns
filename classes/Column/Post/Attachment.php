<?php

declare(strict_types=1);

namespace AC\Column\Post;

use AC\Column;
use AC\Column\Renderable;
use AC\Setting\Config;
use AC\Setting\SettingCollection;
use AC\Settings\Column\AttachmentDisplay;

class Attachment extends Column implements Column\Value
{

    public function __construct()
    {
        parent::__construct(
            'column-attachment',
            __('Attachments', 'codepress-admin-columns'),
            new SettingCollection([
                new AttachmentDisplay(),
            ])
        );
    }

    public function renderable(): Renderable
    {
        return new Column\Post\Renderable\Attachment(
            new Renderable\ValueFormatter($this->get_settings())
        );
    }

    public function get_raw_value($post_id)
    {
        return $this->get_attachment_ids((int)$post_id);
    }

    public function get_attachment_ids(int $post_id): array
    {
        $attachment_ids = get_posts([
            'post_type'      => 'attachment',
            'posts_per_page' => -1,
            'post_status'    => null,
            'post_parent'    => $post_id,
            'fields'         => 'ids',
        ]);

        if ( ! $attachment_ids) {
            return [];
        }

        return $attachment_ids;
    }

    public function register_settings()
    {
        $this->add_setting(new AttachmentDisplay());
    }

}