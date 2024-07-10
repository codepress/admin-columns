<?php

declare(strict_types=1);

namespace AC\Value\Extended;

use AC\ApplyFilter\ValidAudioMimetypes;
use AC\ApplyFilter\ValidVideoMimetypes;
use AC\Value\ExtendedValueLink;
use AC\View\Embed\Video;

class MediaPreview implements ExtendedValue
{

    private const NAME = 'media-preview';

    public function get_link(int $id, string $label): ExtendedValueLink
    {
        return new ExtendedValueLink($label, $id, self::NAME);
    }

    public function can_render(string $view): bool
    {
        return $view === self::NAME;
    }

    private function get_media_type($id): ?string
    {
        $mime_type = get_post_field('post_mime_type', $id);

        switch (true) {
            case in_array($mime_type, (new ValidVideoMimetypes())->apply_filters(), true):
                return 'video';

            case in_array($mime_type, (new ValidAudioMimetypes())->apply_filters(), true):
                return 'audio';

            case wp_get_attachment_image_src($id):
                return 'image';

            default:
                return null;
        }
    }

    public function render(int $id, array $params): string
    {
        switch ($this->get_media_type($id)) {
            case 'audio':
                return sprintf(
                    '<audio controls autoplay="autoplay" preload="none" src="%s">%s</audio>',
                    esc_url(wp_get_attachment_url($id)),
                    __('No support for audio player', 'codepress-admin-columns')
                );
            case 'video':
                return (new Video([]))
                    ->set_src(wp_get_attachment_url($id))
                    ->render();

            case 'image':
                return sprintf('<img src="%s" alt="">', esc_url(wp_get_attachment_url($id)));
        }

        return __('Preview not available', 'codepress-admin-columns');
    }

}