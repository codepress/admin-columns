<?php

namespace AC\Helper;

use DOMDocument;
use DOMElement;

class Image
{

    /**
     * Resize image
     *
     * @param string      $file
     * @param int         $max_w
     * @param int         $max_h
     * @param bool        $crop
     * @param null|string $suffix
     * @param null|string $dest_path
     * @param int         $jpeg_quality
     *
     * @return false|string
     */
    public function resize(
        $file,
        $max_w,
        $max_h,
        $crop = false,
        $suffix = null,
        $dest_path = null,
        $jpeg_quality = 90
    ) {
        $editor = wp_get_image_editor($file);

        if (is_wp_error($editor)) {
            return false;
        }

        $editor->set_quality($jpeg_quality);

        $resized = $editor->resize($max_w, $max_h, $crop);

        if (is_wp_error($resized)) {
            return false;
        }

        $dest_file = $editor->generate_filename($suffix, $dest_path);
        $saved = $editor->save($dest_file);

        if (is_wp_error($saved)) {
            return false;
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

    public function get_image_by_id(int $id, $size): ?string
    {
        $attributes = wp_get_attachment_image_src($id, $size);

        // Is Image
        if ($attributes) {
            [$src, $width, $height] = $attributes;

            if (is_array($size)) {
                return $this->markup_cover($src, (int)$size[0], (int)$size[1], $id);
            }

            if (is_string($size)) {
                // In case of SVG
                if (1 === (int)$width && 1 === (int)$height) {
                    $_size = $this->get_image_sizes_by_name($size);
                    $width = $_size['width'];
                    $height = $_size['height'];
                }

                return $this->markup($src, $width, $height, $id);
            }

            return null;
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

    private function scale_size(int $size, float $scale = 1): float
    {
        return round(abs($size) * $scale);
    }

    private function is_resized_image(string $path): bool
    {
        $fileinfo = pathinfo($path);

        return (bool)preg_match('/-[0-9]+x[0-9]+$/', $fileinfo['filename']);
    }

    public function get_image_by_url(string $url, $size): string
    {
        if (is_string($size) && ($sizes = $this->get_image_sizes_by_name($size))) {
            $dimensions = [$sizes['width'], $sizes['height']];
        } elseif (is_array($size)) {
            $dimensions = $size;
        } else {
            $dimensions = [60, 60];
        }

        $image_path = str_replace(WP_CONTENT_URL, WP_CONTENT_DIR, $url);

        if (is_file($image_path)) {
            // try to resize image if it is not already resized
            if ( ! $this->is_resized_image($image_path) && ($resized = $this->resize(
                    $image_path,
                    $dimensions[0],
                    $dimensions[1],
                    true
                ))) {
                $src = str_replace(WP_CONTENT_DIR, WP_CONTENT_URL, $resized);

                return $this->markup($src, $dimensions[0], $dimensions[1]);
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
    public function get_image($image, $size = 'thumbnail', bool $skip_image_check = false)
    {
        return implode($this->get_images($image, $size, $skip_image_check));
    }

    public function get_image_sizes_by_name(string $name): array
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
        return pathinfo($this->get_file_name($attachment_id), PATHINFO_EXTENSION);
    }

    private function get_file_tooltip_attr($media_id)
    {
        return ac_helper()->html->get_tooltip_attr($this->get_file_name($media_id));
    }

    // TODO create View
    private function markup_cover(string $src, int $width, int $height, int $media_id = null)
    {
        ob_start(); ?>

		<span class="ac-image -cover" data-media-id="<?= esc_attr($media_id) ?>">
			<img style="width:<?= esc_attr($width) ?>px;height:<?= esc_attr($height) ?>px;" src="<?= esc_attr(
                $src
            ) ?>" alt="">
		</span>

        <?php
        return ob_get_clean();
    }

    private function markup($src, $width, $height, $media_id = null, $add_extension = false, $class = '')
    {
        if ($media_id && ! wp_attachment_is_image($media_id)) {
            $class = ' ac-icon';
        }

        ob_start(); ?>
		<span class="ac-image <?= esc_attr($class); ?>" data-media-id="<?= esc_attr(
            $media_id
        ); ?>" <?= $this->get_file_tooltip_attr($media_id); ?>>
			<img style="max-width:<?= esc_attr($width); ?>px;max-height:<?= esc_attr($height); ?>px;" src="<?= esc_attr(
                $src
            ); ?>" alt="">

			<?php
            if ($add_extension) : ?>
				<span class="ac-extension"><?= esc_attr($this->get_file_extension($media_id)); ?></span>
            <?php
            endif; ?>

		</span>

        <?php
        return ob_get_clean();
    }

    /**
     * Return dimensions and file type
     *
     * @param string $url
     *
     * @return false|array
     * @see filesize
     */
    public function get_local_image_info($url)
    {
        $path = $this->get_local_image_path($url);

        if ( ! $path) {
            return false;
        }

        return getimagesize($path);
    }

    /**
     * @param string $url
     *
     * @return false|string
     */
    public function get_local_image_path($url)
    {
        $path = str_replace(WP_CONTENT_URL, WP_CONTENT_DIR, $url);

        if ( ! file_exists($path)) {
            return false;
        }

        return $path;
    }

    public function get_local_image_size($url): ?int
    {
        $path = $this->get_local_image_path($url);

        return $path
            ? filesize($path)
            : null;
    }

    /**
     * @param string $string
     *
     * @return array
     */
    public function get_image_urls_from_string($string): array
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