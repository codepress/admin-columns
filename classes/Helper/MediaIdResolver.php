<?php

declare(strict_types=1);

namespace AC\Helper;

class MediaIdResolver extends Creatable
{
    public function get_attachment_id_by_url(string $image_url, bool $check_cropped_versions = false): ?int
    {
        if (! $image_url) {
            return null;
        }

        $upload_dir = wp_get_upload_dir();

        // Normalize the scheme (http vs https) on both URLs so a valid local upload isn't rejected on a scheme mismatch.
        $image_url = set_url_scheme($image_url);
        $baseurl = set_url_scheme($upload_dir['baseurl']);

        // Is image in upload folder?
        if (false === strpos($image_url, $baseurl)) {
            return null;
        }

        $file_with_relative_path = ltrim(str_replace($baseurl, '', $image_url), '/');

        $images = get_posts([
            'post_type'  => 'attachment',
            'fields'     => 'ids',
            'meta_query' => [
                [
                    'key'   => '_wp_attached_file',
                    'value' => $file_with_relative_path,
                ],
            ],
            'posts_per_page' => 1,
        ]);

        if ($images) {
            return (int)$images[0];
        }

        // Maybe it's a cropped version of an image. e.g. file-name-320x60.jpg
        if ($check_cropped_versions) {
            return $this->get_cropped_attachment_id_by_url($image_url, $file_with_relative_path);
        }

        return null;
    }

    private function get_cropped_attachment_id_by_url(string $image_url, string $file_with_relative_path): ?int
    {
        $relative_upload_dir = dirname($file_with_relative_path);

        $image_ids = get_posts([
            'post_type'  => 'attachment',
            'fields'     => 'ids',
            'meta_query' => [
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

        if (! $image_ids) {
            return null;
        }

        // Direct match
        if (1 === count($image_ids)) {
            return (int)$image_ids[0];
        }

        // Make sure the found image is in the same folder as the original
        $image_id = null;

        foreach ($image_ids as $_image_id) {
            if (0 === strpos((string) get_post_meta($_image_id, '_wp_attached_file', true), $relative_upload_dir)) {
                $image_id = $_image_id;
                break;
            }
        }

        return $image_id === null ? null : (int)$image_id;
    }

}
