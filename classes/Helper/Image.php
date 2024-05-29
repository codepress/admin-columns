<?php

namespace AC\Helper;

use DOMDocument;
use DOMElement;

class Image
{

    public function resize(
        string $file,
        int $max_w,
        int $max_h,
        bool $crop = false,
        string $suffix = null,
        string $dest_path = null,
        int $jpeg_quality = 90
    ): ?string {
        $editor = wp_get_image_editor($file);

        if (is_wp_error($editor)) {
            return null;
        }

        $editor->set_quality($jpeg_quality);

        $resized = $editor->resize($max_w, $max_h, $crop);

        if (is_wp_error($resized)) {
            return null;
        }

        $dest_file = $editor->generate_filename($suffix, $dest_path);
        $saved = $editor->save($dest_file);

        if (is_wp_error($saved)) {
            return null;
        }

        return $dest_file;
    }

    /**
     * @param int[]|int    $ids
     * @param array|string $size
     *
     * @return string HTML Images
     */
    public function get_images_by_ids($ids, $size)
    {
        $images = [];

        $ids = is_array($ids) ? $ids : [$ids];
        foreach ($ids as $id) {
            $images[] = $this->get_image_by_id($id, $size);
        }

        return implode($images);
    }

    /**
     * @param int          $id
     * @param string|array $size
     *
     * @return string|false
     */
    public function get_image_by_id($id, $size)
    {
        if ( ! is_numeric($id)) {
            return false;
        }

        $attributes = wp_get_attachment_image_src($id, $size);

        // Is Image
        if ($attributes) {
            [$src, $width, $height] = $attributes;

            if (is_array($size)) {
                $image = $this->markup_cover($src, $size[0], $size[1], $id);
            } else {
                // In case of SVG
                if ('svg' === pathinfo($src, PATHINFO_EXTENSION) && 'full' !== $size) {
                    $_size = $this->get_image_sizes_by_name($size);
                    $width = $_size['width'];
                    $height = $_size['height'];
                }
                $image = $this->markup($src, $width, $height, $id);
            }

            return $image;
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

        return false;
    }

    /**
     * @param     $size
     * @param int $scale
     *
     * @return float
     */
    private function scale_size($size, $scale = 1): float
    {
        return round(absint($size) * $scale);
    }

    private function is_resized_image($path)
    {
        $fileinfo = pathinfo($path);

        return preg_match('/-[0-9]+x[0-9]+$/', $fileinfo['filename']);
    }

    // TODO test
    public function get_image_by_url(string $url, $size): string
    {
        $dimensions = [60, 60];

        if (is_string($size)) {
            $sizes = $this->get_image_sizes_by_name($size);

            if ($sizes) {
                $dimensions = [$sizes['width'], $sizes['height']];
            }
        } elseif (is_array($size)) {
            $dimensions = $size;
        }

        $image_path = str_replace(WP_CONTENT_URL, WP_CONTENT_DIR, $url);

        if (is_file($image_path)) {
            // try to resize image if it is not already resized
            if ( ! $this->is_resized_image($image_path)) {
                $resized = $this->resize(
                    $image_path,
                    $dimensions[0],
                    $dimensions[1],
                    true
                );

                if ($resized) {
                    $src = str_replace(WP_CONTENT_DIR, WP_CONTENT_URL, $resized);

                    return $this->markup($src, $dimensions[0], $dimensions[1]);
                }
            }

            return $this->markup($url, $dimensions[0], $dimensions[1]);
        }

        // External image
        return $this->markup_cover($image_path, $dimensions[0], $dimensions[1]);
    }

    /**
     * @param mixed        $images
     * @param array|string $size
     * @param bool         $skip_image_check Skips image check. Useful when the url does not have an image extension like jpg or gif (e.g. gravatar).
     *
     * @return array
     */
    public function get_images($images, $size = 'thumbnail', $skip_image_check = false)
    {
        $thumbnails = [];

        foreach ((array)$images as $value) {
            if ($skip_image_check && $value && is_string($value)) {
                $thumbnails[] = $this->get_image_by_url($value, $size);
            } elseif (ac_helper()->string->is_image($value)) {
                $thumbnails[] = $this->get_image_by_url($value, $size);
            } // Media Attachment
			elseif (is_numeric($value) && wp_get_attachment_url($value)) {
                $thumbnails[] = $this->get_image_by_id($value, $size);
            }
        }

        return $thumbnails;
    }

    /**
     * @param int|string   $image ID of Url
     * @param string|array $size
     * @param bool         $skip_image_check
     *
     * @return string
     */
    // TODO
    public function get_image($image, $size = 'thumbnail', bool $skip_image_check = false)
    {
        return implode($this->get_images($image, $size, $skip_image_check));
    }

    public function get_image_sizes_by_name(string $name): array
    {
        $available_sizes = wp_get_additional_image_sizes();

        $defaults = ['thumbnail', 'medium', 'large'];
        foreach ($defaults as $key) {
            $available_sizes[$key] = [
                'width'  => get_option($key . '_size_w'),
                'height' => get_option($key . '_size_h'),
            ];
        }

        $sizes = false;

        if (is_scalar($name) && isset($available_sizes[$name])) {
            $sizes = $available_sizes[$name];
        }

        return $sizes;
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

    private function markup($src, $width, $height, $media_id = null, $add_extension = false, string $class = '')
    {
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

        ob_start(); ?>
		<span class="ac-image <?= esc_attr($class); ?>" data-media-id="<?= esc_attr(
            $media_id
        ); ?>" <?= $this->get_file_tooltip_attr($media_id) ?>>
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

    /**
     * Return dimensions and file type
     */
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