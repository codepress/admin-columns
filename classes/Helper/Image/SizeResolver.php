<?php

declare(strict_types=1);

namespace AC\Helper\Image;

use AC\Helper\Creatable;

class SizeResolver extends Creatable
{
    /**
     * @param string|array $size
     */
    public function get_dimensions($size): array
    {
        if (is_string($size)) {
            $sizes = $this->get_sizes_by_name($size);

            if ($sizes) {
                return [
                    (int)$sizes['width'],
                    (int)$sizes['height'],
                ];
            }
        }

        $pair = $this->normalize_pair($size);

        if ($pair !== null) {
            return $pair;
        }

        return [60, 60];
    }

    /**
     * Normalize a numeric width/height pair (e.g. [300, 200]) or return null when the input is not such a pair.
     *
     * @param string|array $size
     */
    public function normalize_pair($size): ?array
    {
        if (
            is_array($size)
            && isset($size[0], $size[1])
            && is_numeric($size[0])
            && is_numeric($size[1])
            && $size[0] > 0
            && $size[1] > 0
        ) {
            return [
                (int)$size[0],
                (int)$size[1],
            ];
        }

        return null;
    }

    public function get_sizes_by_name(string $name): array
    {
        $available_sizes = wp_get_additional_image_sizes();

        foreach (['thumbnail', 'medium', 'large'] as $key) {
            $available_sizes[$key] = [
                'width'  => (int)get_option($key . '_size_w'),
                'height' => (int)get_option($key . '_size_h'),
            ];
        }

        return $available_sizes[$name] ?? [];
    }

}
