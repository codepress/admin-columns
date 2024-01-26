<?php

namespace AC\Column\Media;

use AC\Column;
use AC\Settings;

class AvailableSizes extends Column
{

    public function __construct()
    {
        $this->set_type('column-available_sizes')
             ->set_group('media-image')
             ->set_label(__('Available Sizes', 'codepress-admin-columns'));
    }

    public function get_value($id): string
    {
        $meta = get_post_meta($id, '_wp_attachment_metadata', true);

        // TODO test
        $sizes = $meta['sizes'] ?? null;

        if ( ! $sizes) {
            return $this->get_empty_char();
        }

        $paths = [];

        $available_sizes = $this->get_available_sizes($sizes);

        if ($available_sizes) {
            $url = wp_get_attachment_url($id);
            $paths[] = ac_helper()->html->tooltip(
                ac_helper()->html->link($url, __('original', 'codepress-admin-columns')),
                basename($url)
            );

            foreach ($available_sizes as $size) {
                $src = wp_get_attachment_image_src($id, $size);

                if ( ! empty($src[0])) {
                    $paths[] = ac_helper()->html->tooltip(ac_helper()->html->link($src[0], $size), basename($src[0]));
                }
            }
        }

        // include missing image sizes?

        if ('1' === $this->get_option('include_missing_sizes')) {
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

        return "<div class='ac-image-sizes'>" . implode(ac_helper()->html->divider(), $paths) . "</div>";
    }

    public function get_available_sizes(array $image_sizes): array
    {
        return array_intersect(array_keys($image_sizes), get_intermediate_image_sizes());
    }

    public function get_missing_sizes(array $image_sizes): array
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

    public function register_settings()
    {
        $this->add_setting(new Settings\Column\MissingImageSize());
    }

}