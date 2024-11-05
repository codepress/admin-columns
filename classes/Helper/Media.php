<?php

namespace AC\Helper;

class Media
{

    public function get_attachment_id_by_url(string $image_url, bool $check_cropped_versions = false): ?int
    {
        if ( ! $image_url) {
            return null;
        }

        $upload_dir = wp_get_upload_dir();

        // Is image in upload folder?
        if (false === strpos($image_url, $upload_dir['baseurl'])) {
            return null;
        }

        $file_with_relative_path = ltrim(str_replace($upload_dir['baseurl'], '', $image_url), '/');

        $image_id = null;

        $images = get_posts([
            'post_type'      => 'attachment',
            'fields'         => 'ids',
            'meta_query'     => [
                [
                    'key'   => '_wp_attached_file',
                    'value' => $file_with_relative_path,
                ],
            ],
            'posts_per_page' => 1,
        ]);

        if ($images) {
            $image_id = $images[0];
        }

        // Maybe it's a cropped version of an image. e.g. file-name-320x60.jpg
        if ( ! $image_id && $check_cropped_versions) {
            $relative_upload_dir = dirname($file_with_relative_path);

            $image_ids = get_posts([
                'post_type'      => 'attachment',
                'fields'         => 'ids',
                'meta_query'     => [
                    [
                        'key'     => '_wp_attachment_metadata',
                        'value'   => serialize(basename($image_url)),
                        'compare' => 'LIKE',
                    ],
                    [
                        'key'     => '_wp_attached_file',
                        'value'   => $relative_upload_dir,
                        'compare' => 'LIKE',
                    ],
                ],
                'posts_per_page' => 1,
            ]);

            if ($image_ids) {
                if (1 === count($image_ids)) {
                    // Direct match
                    $image_id = $image_ids[0];
                } else {
                    // Make sure the found image is in the same folder as the original
                    foreach ($image_ids as $_image_id) {
                        if (0 === strpos(get_post_meta($_image_id, '_wp_attached_file', true), $relative_upload_dir)) {
                            $image_id = $_image_id;
                        }
                    }
                }
            }
        }

        return (int)$image_id;
    }

}