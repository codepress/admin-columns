<?php

declare(strict_types=1);

namespace AC\Helper\Image;

use AC\Helper\Creatable;
use AC\Helper\Html;
use AC\View;

class Markup extends Creatable
{
    public function image(string $src, int $width, int $height, ?int $media_id = null, string $tooltip = ''): string
    {
        $class = '';

        if ($media_id && ! wp_attachment_is_image($media_id)) {
            $class = ' ac-icon';
        }

        $image_attributes = [
            'max-width'  => esc_attr((string)$width) . 'px',
            'max-height' => esc_attr((string)$height) . 'px',
        ];

        if (pathinfo($src, PATHINFO_EXTENSION) === 'svg') {
            $image_attributes['width'] = esc_attr((string)$width) . 'px';
            $image_attributes['height'] = esc_attr((string)$height) . 'px';
        }

        return (new View([
            'src'          => $src,
            'class'        => $class,
            'styles'       => Html::create()->get_inline_styles($image_attributes),
            'tooltip_attr' => $tooltip,
            'media_id'     => $media_id,
        ]))->set_template('column/image')->render();
    }

    public function cover(string $src, int $width, int $height, ?int $media_id = null): string
    {
        return (new View([
            'src'      => $src,
            'width'    => $width,
            'height'   => $height,
            'media_id' => $media_id,
        ]))->set_template('column/image-cover')->render();
    }

    public function file_pill(int $media_id, string $filename): string
    {
        $extension = (string)pathinfo($filename, PATHINFO_EXTENSION);

        return sprintf(
            '<span class="ac-file-pill" data-media-id="%s">%s</span>',
            esc_attr((string)$media_id),
            Html::create()->file_pill($extension, $filename)
        );
    }

}
