<?php

declare(strict_types=1);

namespace AC\Value\Formatter\Media;

use AC\ApplyFilter\ValidAudioMimetypes;
use AC\ApplyFilter\ValidVideoMimetypes;
use AC\Exception\ValueNotFoundException;
use AC\Setting\Formatter;
use AC\Type\Value;
use AC\Value\Extended\ExtendedValue;

class PreviewViewLink implements Formatter
{

    private $extended_view;

    public function __construct(ExtendedValue $extended_view)
    {
        $this->extended_view = $extended_view;
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

    public function format(Value $value): Value
    {
        if ( ! $this->get_media_type($value->get_id())) {
            throw ValueNotFoundException::from_id($value->get_id());
        }

        $link = $this->extended_view->get_link()
                                    ->with_class("-nopadding -preview")
                                    ->with_title(get_the_title($value->get_id()))
                                    ->with_edit_link(get_edit_post_link($value->get_id()))
                                    ->with_download_link(wp_get_attachment_url($value->get_id()));

        return $value->with_value($link->render(__('View', 'codepress-admin-columns'), $value->get_id()));
    }

}