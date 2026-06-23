<?php

declare(strict_types=1);

namespace AC\Helper;

use AC\Helper\Image\Markup;
use AC\Helper\Image\SizeResolver;

class Image extends Creatable
{
    public function resize(string $file, int $max_w, int $max_h, bool $crop = false): ?string
    {
        $editor = wp_get_image_editor($file);

        if (is_wp_error($editor)) {
            return null;
        }

        $editor->set_quality(90);

        $resized = $editor->resize($max_w, $max_h, $crop);

        if (is_wp_error($resized)) {
            return null;
        }

        $filename = $editor->generate_filename();
        $saved = $editor->save($filename);

        if (is_wp_error($saved)) {
            return null;
        }

        return $filename;
    }

    public function get_image_by_id(int $id, $size): ?string
    {
        if (! wp_get_attachment_url($id)) {
            return null;
        }

        $attributes = wp_get_attachment_image_src($id, $size);

        // Is Image
        if ($attributes) {
            [$src, $width, $height] = $attributes;

            $pair = SizeResolver::create()->normalize_pair($size);

            if ($pair !== null) {
                return Markup::create()->cover($src, $pair[0], $pair[1], $id);
            }

            // In case of SVG
            if (is_string($size) && 'svg' === pathinfo($src, PATHINFO_EXTENSION) && 'full' !== $size) {
                $_size = SizeResolver::create()->get_sizes_by_name($size);

                $width = (int)($_size['width'] ?? 0);
                $height = (int)($_size['height'] ?? 0);
            }

            return $this->markup_image($src, (int)$width, (int)$height, $id);
        }

        // Is File - render as pill instead of mime-type icon
        if (wp_get_attachment_image_src($id, $size, true)) {
            $filename = LocalFile::create()->get_file_name($id) ?? '';

            return Markup::create()->file_pill($id, $filename);
        }

        return null;
    }

    public function get_image_by_url(string $url, $size): ?string
    {
        $dimensions = SizeResolver::create()->get_dimensions($size);

        $width = $dimensions[0] ?? 0;
        $height = $dimensions[1] ?? 0;

        $local = LocalFile::create();
        $image_path = $local->url_to_path($url);

        if (is_file($image_path)) {
            // try to resize image if it is not already resized
            if (! $this->is_resized_image($image_path)) {
                $resized = $this->resize(
                    $image_path,
                    $width,
                    $height,
                    true
                );

                if ($resized) {
                    $src = $local->path_to_url($resized);

                    return $this->markup_image($src, $width, $height);
                }
            }

            return $this->markup_image($url, $width, $height);
        }

        // External image
        return Markup::create()->cover($image_path, $width, $height);
    }

    public function get_image(string $image_id_or_url, $size = 'thumbnail'): ?string
    {
        if (! $image_id_or_url) {
            return null;
        }

        if (is_numeric($image_id_or_url)) {
            return $this->get_image_by_id((int)$image_id_or_url, $size);
        }

        if (Strings::create()->is_image($image_id_or_url)) {
            return $this->get_image_by_url($image_id_or_url, $size);
        }

        return null;
    }

    /**
     * @deprecated 7.1 Use AC\Helper\LocalFile::create()->get_file_name() instead.
     */
    public function get_file_name(int $attachment_id): ?string
    {
        return LocalFile::create()->get_file_name($attachment_id);
    }

    /**
     * @deprecated 7.1 Use AC\Helper\LocalFile::create()->get_file_extension() instead.
     */
    public function get_file_extension(int $attachment_id): string
    {
        return LocalFile::create()->get_file_extension($attachment_id);
    }

    public function get_local_image_info(string $url): ?array
    {
        $path = LocalFile::create()->get_path($url);

        if (! $path) {
            return null;
        }

        return getimagesize($path) ?: null;
    }

    /**
     * @deprecated 7.1 Use AC\Helper\LocalFile::create()->get_path() instead.
     */
    public function get_local_image_path(string $url): ?string
    {
        return LocalFile::create()->get_path($url);
    }

    /**
     * @deprecated 7.1 Use AC\Helper\LocalFile::create()->get_size() instead.
     */
    public function get_local_image_size(string $url): ?int
    {
        return LocalFile::create()->get_size($url);
    }

    private function markup_image(string $src, int $width, int $height, ?int $media_id = null): string
    {
        $tooltip = $media_id
            ? Html::create()->get_tooltip_attr(LocalFile::create()->get_file_name($media_id) ?? '')
            : '';

        return Markup::create()->image($src, $width, $height, $media_id, $tooltip);
    }

    private function is_resized_image(string $path): bool
    {
        $fileinfo = pathinfo($path);

        return (bool)preg_match('/-[0-9]+x[0-9]+$/', $fileinfo['filename']);
    }

}
