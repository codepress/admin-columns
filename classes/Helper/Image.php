<?php

namespace AC\Helper;

use DOMDocument;
use DOMElement;

class Image
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
        if ( ! wp_get_attachment_url($id)) {
            return null;
        }

        $attributes = wp_get_attachment_image_src($id, $size);

        // Is Image
        if ($attributes) {
            [$src, $width, $height] = $attributes;

            if (is_array($size)) {
                return $this->markup_cover($src, $size[0], $size[1], $id);
            }

            // In case of SVG
            if (is_string($size) && 'svg' === pathinfo($src, PATHINFO_EXTENSION) && 'full' !== $size) {
                $_size = $this->get_image_sizes_by_name($size);
                $width = $_size['width'];
                $height = $_size['height'];
            }

            return $this->markup($src, $width, $height, $id);
        }

        $attributes = wp_get_attachment_image_src($id, $size, true);

        // Is File, use icon
        if ($attributes) {
            return $this->markup(
                $attributes[0],
                $this->scale_size($attributes[1], 0.8),
                $this->scale_size($attributes[2], 0.8),
                $id,
                true
            );
        }

        return null;
    }

    private function scale_size($size, float $scale = 1): float
    {
        return round(absint($size) * $scale);
    }

    private function is_resized_image(string $path): bool
    {
        $fileinfo = pathinfo($path);

        return (bool)preg_match('/-[0-9]+x[0-9]+$/', $fileinfo['filename']);
    }

    /**
     * @param $size
     *
     * @return array [ $width, $height ]
     */
    private function get_dimensions_by_sizename($size): array
    {
        if (is_string($size)) {
            $sizes = $this->get_image_sizes_by_name($size);

            if ($sizes) {
                return [
                    $sizes['width'],
                    $sizes['height'],
                ];
            }
        }

        if (is_array($size)) {
            return $size;
        }

        return [60, 60];
    }

    public function get_image_by_url(string $url, $size): string
    {
        $dimensions = $this->get_dimensions_by_sizename($size);

        $width = $dimensions[0];
        $height = $dimensions[1];

        $image_path = str_replace(WP_CONTENT_URL, WP_CONTENT_DIR, $url);

        if (is_file($image_path)) {
            // try to resize image if it is not already resized
            if ( ! $this->is_resized_image($image_path)) {
                $resized = $this->resize(
                    $image_path,
                    $width,
                    $height,
                    true
                );

                if ($resized) {
                    $src = str_replace(WP_CONTENT_DIR, WP_CONTENT_URL, $resized);

                    return $this->markup($src, $width, $height);
                }
            }

            return $this->markup($url, $width, $height);
        }

        // External image
        return $this->markup_cover($image_path, $width, $height);
    }

    // TODO check usages
    public function get_image($image_id_or_url, $size = 'thumbnail', bool $skip_image_check = false): ?string
    {
        if ( ! $image_id_or_url) {
            return null;
        }

        if (is_string($image_id_or_url) && ($skip_image_check || ac_helper()->string->is_image($image_id_or_url))) {
            return $this->get_image_by_url($image_id_or_url, $size);
        }

        // Media Attachment
        if (is_numeric($image_id_or_url)) {
            return $this->get_image_by_id($image_id_or_url, $size);
        }

        return null;
    }

    private function get_image_sizes_by_name(string $name): array
    {
        $available_sizes = wp_get_additional_image_sizes();

        foreach (['thumbnail', 'medium', 'large'] as $key) {
            $available_sizes[$key] = [
                'width'  => get_option($key . '_size_w'),
                'height' => get_option($key . '_size_h'),
            ];
        }

        return $available_sizes[$name] ?? [];
    }

    public function get_file_name(int $attachment_id): ?string
    {
        $file = get_post_meta($attachment_id, '_wp_attached_file', true);

        if ( ! $file) {
            return null;
        }

        return basename($file);
    }

    public function get_file_extension(int $attachment_id): string
    {
        return (string)pathinfo($this->get_file_name($attachment_id), PATHINFO_EXTENSION);
    }

    private function get_file_tooltip_attr(int $media_id): string
    {
        return ac_helper()->html->get_tooltip_attr($this->get_file_name($media_id));
    }

    private function markup_cover($src, $width, $height, $media_id = null)
    {
        ob_start(); ?>

		<span class="ac-image -cover" data-media-id="<?= esc_attr($media_id); ?>">
			<img style="width:<?= esc_attr($width); ?>px;height:<?= esc_attr($height); ?>px;" src="<?= esc_attr(
                $src
            ); ?>" alt="">
		</span>

        <?php
        return ob_get_clean();
    }

    private function markup($src, $width, $height, $media_id = null, $add_extension = false)
    {
        $class = '';

        if ($media_id && ! wp_attachment_is_image($media_id)) {
            $class = ' ac-icon';
        }

        $image_attributes = [
            'max-width'  => esc_attr($width) . 'px',
            'max_height' => esc_attr($height) . 'px',
        ];

        if (pathinfo($src, PATHINFO_EXTENSION) === 'svg') {
            $image_attributes['width'] = esc_attr($width) . 'px';
            $image_attributes['height'] = esc_attr($height) . 'px';
        }

        $tooltip_attr = $media_id ? $this->get_file_tooltip_attr($media_id) : '';

        ob_start(); ?>
		<span class="ac-image<?= $class ?>" data-media-id="<?= esc_attr($media_id); ?>" <?= $tooltip_attr ?>>
			<img style="<?= ac_helper()->html->get_style_attributes_as_string($image_attributes) ?>"
					src="<?= esc_attr($src) ?>" alt="">

			<?php
            if ($add_extension) : ?>
				<span class="ac-extension"><?= esc_attr($this->get_file_extension($media_id)) ?></span>
            <?php
            endif; ?>

		</span>

        <?php
        return ob_get_clean();
    }

    public function get_local_image_info(string $url): ?array
    {
        $path = $this->get_local_image_path($url);

        if ( ! $path) {
            return null;
        }

        return getimagesize($path);
    }

    public function get_local_image_path(string $url): ?string
    {
        $path = str_replace(WP_CONTENT_URL, WP_CONTENT_DIR, $url);

        if ( ! file_exists($path)) {
            return null;
        }

        return $path;
    }

    public function get_local_image_size(string $url): ?int
    {
        $path = $this->get_local_image_path($url);

        return $path
            ? filesize($path)
            : null;
    }

    public function get_image_urls_from_string(string $string): array
    {
        if ( ! $string) {
            return [];
        }

        if (false === strpos($string, '<img')) {
            return [];
        }

        if ( ! class_exists('DOMDocument')) {
            return [];
        }

        $dom = new DOMDocument();

        libxml_use_internal_errors(true);
        $dom->loadHTML($string);
        $dom->preserveWhiteSpace = false;
        libxml_clear_errors();

        $urls = [];

        $images = $dom->getElementsByTagName('img');

        foreach ($images as $img) {
            /** @var DOMElement $img */
            $src = $img->getAttribute('src');

            if ( ! $src) {
                continue;
            }

            $urls[] = $src;
        }

        return $urls;
    }

}