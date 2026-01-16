<?php

declare(strict_types=1);

namespace AC\Formatter\Media;

use AC\Exception\ValueNotFoundException;
use AC\Formatter;
use AC\Type\Value;

class AvailableSizes implements Formatter
{

    private $include_missing_file_sizes;

    public function __construct(bool $include_missing_file_sizes)
    {
        $this->include_missing_file_sizes = $include_missing_file_sizes;
    }

    public function format(Value $value): Value
    {
        $meta = get_post_meta($value->get_id(), '_wp_attachment_metadata', true);
        $sizes = $meta['sizes'] ?? null;

        if ( ! $sizes) {
            throw new ValueNotFoundException();
        }

        $paths = [];

        $available_sizes = $this->get_available_sizes($sizes);

        if ($available_sizes) {
            $url = wp_get_attachment_url($value->get_id());
            $paths[] = ac_helper()->html->tooltip(
                ac_helper()->html->link($url, __('original', 'codepress-admin-columns')),
                basename($url)
            );

            foreach ($available_sizes as $size) {
                $src = (array)wp_get_attachment_image_src($value->get_id(), $size);

                if ( ! empty($src[0])) {
                    $paths[] = ac_helper()->html->tooltip(ac_helper()->html->link($src[0], $size), basename($src[0]));
                }
            }
        }

        if ($this->include_missing_file_sizes) {
            $missing = $this->get_missing_sizes($sizes);

            if ($missing) {
                foreach ($missing as $size) {
                    $paths[] = ac_helper()->html->tooltip(
                        $size,
                        sprintf(
                            __('Missing image file for size %s.', 'codepress-admin-columns'),
                            '<em>"' . $size . '"</em>'
                        ),
                        ['class' => 'ac-missing-size']
                    );
                }
            }
        }

        return $value->with_value(
            "<div class='ac-image-sizes'>" . implode(ac_helper()->html->divider(), $paths) . "</div>"
        );
    }

    private function get_available_sizes(array $image_sizes): array
    {
        return array_intersect(array_keys($image_sizes), get_intermediate_image_sizes());
    }

    private function get_missing_sizes(array $image_sizes): array
    {
        global $_wp_additional_image_sizes;

        if (empty($_wp_additional_image_sizes)) {
            return [];
        }

        $additional_size = $_wp_additional_image_sizes;

        if (isset($additional_size['post-thumbnail'])) {
            unset($additional_size['post-thumbnail']);
        }

        // image does not have these additional sizes rendered yet
        return array_diff(array_keys((array)$additional_size), array_keys($image_sizes));
    }

}